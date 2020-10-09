<?php

declare(strict_types=1);

namespace Okaruto\Space\Business\Token;

use Fig\Http\Message\RequestMethodInterface;
use Fig\Http\Message\StatusCodeInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use Okaruto\Space\Config\AdminConfig;

/**
 * Class TokenValidator
 *
 * @package   Okaruto\Space\Business\Token
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class TokenValidator
{

    private const VALIDATION_URL = 'https://cryptostorm.is/pubtokf';

    /** @var bool */
    private $verify;

    /** @var ClientInterface */
    private $client;

    /**
     * TokenValidator constructor.
     *
     * @param AdminConfig     $adminConfig
     * @param ClientInterface $client
     */
    public function __construct(AdminConfig $adminConfig, ClientInterface $client)
    {
        $this->verify = $adminConfig->verifyTokens();
        $this->client = $client;
    }

    /**
     * Returns a TokenValidationResult
     *
     * @param string $token Token to validate
     *
     * @return TokenValidationResult
     */
    public function validate(string $token): TokenValidationResult
    {
        $html = '';

        if ($this->verify) {
            $response = $this->client->request(
                RequestMethodInterface::METHOD_GET,
                self::VALIDATION_URL,
                [
                    RequestOptions::QUERY => [
                        'token' => hash('sha512', $token),

                    ],
                    RequestOptions::HTTP_ERRORS => false,
                ]
            );

            if ($response->getStatusCode() === StatusCodeInterface::STATUS_OK) {
                $html = $response->getBody()->getContents();
            }
        }

        return new TokenValidationResult($this->verifies(), $token, $html);
    }

    /**
     * Return whether validator verfies tokens with cryptostorm
     *
     * @return bool
     */
    public function verifies(): bool
    {
        return $this->verify;
    }
}
