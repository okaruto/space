<?php

declare(strict_types=1);

namespace Okaruto\Space\Command;

use GuzzleHttp\ClientInterface;
use Okaruto\Space\Business\Token\TokenValidationResult;
use Okaruto\Space\Business\Token\TokenValidator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class VerifyTokenCommand
 *
 * @package   Okaruto\Space\Command
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class VerifyTokenCommand extends Command
{

    private const URL = 'https://cryptostorm.is/pubtokf?token=';

    private const REASONS = [
        TokenValidationResult::REASON_UNKNOWN =>
            '<error>Token invalid for unknown reason, please check manually on %s%s</error>',
        TokenValidationResult::REASON_FORMAT_INVALID =>
            '<error>Token is not correctly formatted</error>',
        TokenValidationResult::REASON_TOKEN_INVALID =>
            '<error>Token is invalid</error>',
        TokenValidationResult::REASON_TOKEN_SPENT =>
            '<question>Token was already used, to see how may days you ' .
            'still have please check on %s%s</question>',
    ];

    /** @var ClientInterface */
    private $client;

    /** @var TokenValidator */
    private $tokenValidator;

    /**
     * VerifyTokenCommand constructor.
     *
     * @param ClientInterface $client
     * @param TokenValidator  $tokenValidator
     */
    public function __construct(ClientInterface $client, TokenValidator $tokenValidator)
    {
        $this->client = $client;
        $this->tokenValidator = $tokenValidator;

        parent::__construct();
    }

    /**
     * Configures the command
     */
    protected function configure()
    {
        $this->setName('token:verify')
             ->setDescription('Verify a token with cryptostorm.is/tokenchecker')
             ->addOption(
                 'token',
                 't',
                 InputOption::VALUE_REQUIRED,
                 'Token'
             );
    }

    /**
     * Executes the current command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string|null $token */
        $token = $input->getOption('token');

        return $token === null
            ? $this->messageUsage($output)
            : $this->validateToken($output, $token);
    }

    /**
     * @param OutputInterface $output
     * @param string          $token
     *
     * @return int
     */
    private function validateToken(OutputInterface $output, string $token): int
    {
        $result = $this->tokenValidator->validate($token);

        return $result->valid()
            ? $this->messageValid($output, $result->type())
            : $this->messageInvalid($output, $result, $token);
    }

    /**
     * @param OutputInterface $output
     *
     * @return int
     */
    private function messageUsage(OutputInterface $output): int
    {
        $output->writeln(
            '<comment>Please provide a token to verify with --token</comment>'
        );

        return 0;
    }

    /**
     * @param OutputInterface $output
     * @param string          $type
     *
     * @return int
     */
    private function messageValid(OutputInterface $output, string $type): int
    {
        $output->writeln(
            sprintf(
                '<info>This Token (%s) is valid and unspent</info>',
                $type
            )
        );

        return 0;
    }

    /**
     * @param OutputInterface       $output
     * @param TokenValidationResult $result
     * @param string                $token
     *
     * @return int
     */
    private function messageInvalid(OutputInterface $output, TokenValidationResult $result, string $token): int
    {
        $output->writeln(sprintf(self::REASONS[$result->reasonCode()], self::URL, $token));

        return $result->reasonCode();
    }
}
