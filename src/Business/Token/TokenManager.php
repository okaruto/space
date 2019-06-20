<?php

declare(strict_types=1);

namespace Okaruto\Space\Business\Token;

use Okaruto\Space\Business\Token\Type\TokenTypeCollection;
use Okaruto\Space\Database\PrepareStatementTrait;

/**
 * Class TokenManager
 *
 * @package   Okaruto\Space\Business\Token
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class TokenManager
{

    use PrepareStatementTrait;

    private const SQL_SELECT_AVAILABLE_TOKEN_AMOUNTS = <<<'EOT'
SELECT count(id) as amount, type
FROM tokens_available
GROUP BY type
ORDER BY (
  CASE type
  WHEN '1week' THEN 1
  WHEN '1month' THEN 2
  WHEN '3months' THEN 3
  WHEN '6months' THEN 4
  WHEN '1year' THEN 5
  WHEN '2years' THEN 6
  WHEN 'lifetime' THEN 7 END) ASC;
EOT;

    private const SQL_SELECT_AVAILABLE_TOKEN_TYPE = <<<'EOT'
SELECT type FROM tokens_available WHERE type = :type;
EOT;

    private const SQL_INSERT_NEW_TOKEN = <<<'EOT'
INSERT INTO tokens (value, type) VALUES (:value, :type);
EOT;

    private const SQL_SELECT_RANDOM_TOKEN_TYPE = <<<'EOT'
SELECT id, value, type FROM tokens_available WHERE type = :type ORDER BY RANDOM() LIMIT 1;
EOT;

    private const SQL_SELECT_TOKEN_BY_ID = <<<'EOT'
SELECT id, value, type FROM tokens WHERE id = :id;
EOT;

    private const SQL_DELETE_TOKEN_BY_ID = <<<'EOT'
DELETE FROM tokens WHERE id = :id;
EOT;

    /** @var TokenTypeCollection */
    private $tokenTypeCollection;

    /** @var TokenValidator */
    private $tokenValidator;

    /**
     * TokenManager constructor.
     *
     * @param \PDO                $pdo
     * @param TokenTypeCollection $tokenTypeCollection
     * @param TokenValidator      $tokenValidator
     */
    public function __construct(
        \PDO $pdo,
        TokenTypeCollection $tokenTypeCollection,
        TokenValidator $tokenValidator
    ) {
        $this->pdo = $pdo;
        $this->tokenTypeCollection = $tokenTypeCollection;
        $this->tokenValidator = $tokenValidator;
    }

    /**
     * @return AvailableToken[]
     */
    public function allAvailable(): array
    {
        return array_filter(
            array_map(
                [$this, 'availableTokenInstance'],
                $this->pdo->query(
                    self::SQL_SELECT_AVAILABLE_TOKEN_AMOUNTS
                )->fetchAll(\PDO::FETCH_ASSOC)
            )
        );
    }

    /**
     * @param string $tokenType
     *
     * @return AvailableToken
     */
    public function available(string $tokenType): AvailableToken
    {
        $amount = 0;

        if ($this->tokenTypeCollection->has($tokenType)) {
            $statement = $this->prepareStatement(self::SQL_SELECT_AVAILABLE_TOKEN_TYPE);

            if ($statement->execute(['type' => $tokenType])) {
                $amount = count($statement->fetchAll(\PDO::FETCH_ASSOC));
            }
        }

        return new AvailableToken($amount, $this->tokenTypeCollection->get($tokenType));
    }

    /**
     * @param AvailableTokenInterface $availableToken
     *
     * @return Token
     */
    public function randomToken(AvailableTokenInterface $availableToken): Token
    {
        $id = 0;
        $value = '';
        $type = $this->tokenTypeCollection->get($availableToken->type());

        $statement = $this->prepareStatement(self::SQL_SELECT_RANDOM_TOKEN_TYPE);

        if ($statement->execute(['type' => $availableToken->type()])) {
            $tokenDefinition = $statement->fetchAll(\PDO::FETCH_ASSOC);

            if (count($tokenDefinition) > 0) {
                $tokenDefinition = reset($tokenDefinition);
                $id = (int)$tokenDefinition['id'];
                $value = $tokenDefinition['value'];
            }
        }

        return new Token($id, $value, $type);
    }

    /**
     * @param int $id
     *
     * @return Token
     */
    public function token(int $id): Token
    {
        $statement = $this->prepareStatement(self::SQL_SELECT_TOKEN_BY_ID);

        $value = '';
        $type = null;

        if ($statement->execute(['id' => $id])) {
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

            if (!empty($result)) {
                $definition = reset($result);
                $id = (int)$definition['id'];
                $value = $definition['value'];
                $type = $this->tokenTypeCollection->get($definition['type']);
            }
        }

        return new Token($id, $value, $type);
    }

    /**
     * @param array $rows
     *
     * @return array
     */
    public function addTokens(array $rows): array
    {
        $output = [
            'added' => [],
            'errors' => [],
        ];

        foreach ($rows as $row) {
            $output = $this->tokenValidator->verifies()
                ? $this->addWithAutoTypes($output, trim($row))
                : $this->addWithGivenTypes($output, trim($row));
        }

        return $output;
    }

    /**
     * @param Token $token
     *
     * @return bool
     */
    public function remove(Token $token): bool
    {
        return $this->prepareStatement(self::SQL_DELETE_TOKEN_BY_ID)->execute(['id' => $token->id()]);
    }

    /**
     * @param array $tokenAmount
     *
     * @return null|AvailableToken
     */
    private function availableTokenInstance(array $tokenAmount): ?AvailableToken
    {
        return $this->tokenTypeCollection->has($tokenAmount['type'])
            ? new AvailableToken(
                (int)$tokenAmount['amount'],
                $this->tokenTypeCollection->get($tokenAmount['type'])
            )
            : null;
    }

    /**
     * @param array  $output
     * @param string $row
     *
     * @return array
     */
    private function addWithGivenTypes(array $output, string $row): array
    {
        $parts = explode(';', $row, 2);

        if (count($parts) !== 2) {
            $output['errors']['mangled'][] = $row;
        } else {
            [$token, $type] = $parts;

            $validationResult = $this->tokenValidator->validate($token);

            if ($validationResult->valid()) {
                $output = $this->addToken($token, $type, $output);
            } else {
                $output['errors']['format'][] = join(';', [$token, $type]);
            }
        }

        return $output;
    }

    /**
     * @param array  $output
     * @param string $row
     *
     * @return array
     */
    private function addWithAutoTypes(array $output, string $row): array
    {
        $token = $row;
        $validationResult = $this->tokenValidator->validate($token);

        if ($validationResult->valid()) {
            $output = $this->addToken($token, $validationResult->type(), $output);
        } elseif ($validationResult->reasonFormatInvalid()) {
            $output['errors']['format'][] = $token;
        } elseif ($validationResult->reasonTokenInvalid()) {
            $output['errors']['invalid'][] = $token;
        } elseif ($validationResult->reasonTokenSpent()) {
            $output['errors']['spent'][] = $token;
        } else {
            $output['errors']['unknown'][] = $token;
        }

        return $output;
    }

    /**
     * @param string $token
     * @param string $type
     * @param array  $output
     *
     * @return array
     */
    private function addToken(string $token, string $type, array $output): array
    {
        if ($this->tokenTypeCollection->has($type)) {
            try {
                $this->prepareStatement(self::SQL_INSERT_NEW_TOKEN)->execute(
                    ['value' => $token, 'type' => $type]
                );

                $output['added'][] = join(';', [$token, $type]);
            } catch (\Exception $e) {
                $output['errors']['duplicates'][] = $token;
            }
        } else {
            $output['errors']['unavailable'][] = join(' -> ', [$token, $type]);
        }

        return $output;
    }
}
