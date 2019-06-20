<?php

declare(strict_types=1);

namespace Okaruto\Space\Command;

use Okaruto\Space\Business\Order\Order;
use Okaruto\Space\Business\Order\OrderManager;
use Okaruto\Space\Business\Token\AvailableTokenInterface;
use Okaruto\Space\Business\Token\TokenManager;
use Okaruto\Space\Business\Token\Type\TokenTypeCollection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DispendTokenCommand
 *
 * @package   Okaruto\Space\Command
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class DispenseTokenCommand extends Command
{

    /** @var TokenTypeCollection */
    private $tokenTypeCollection;

    /** @var TokenManager */
    private $tokenManager;

    /** @var OrderManager */
    private $orderManager;

    /**
     * DispenseTokenCommand constructor.
     *
     * @param TokenTypeCollection $tokenTypeCollection
     * @param TokenManager        $tokenManager
     * @param OrderManager        $orderManager
     */
    public function __construct(
        TokenTypeCollection $tokenTypeCollection,
        TokenManager $tokenManager,
        OrderManager $orderManager
    ) {
        $this->tokenManager = $tokenManager;
        $this->orderManager = $orderManager;
        $this->tokenTypeCollection = $tokenTypeCollection;

        parent::__construct();
    }

    /**
     * Configures the command
     */
    protected function configure()
    {
        $this->setName('token:dispense')
             ->setDescription('Dispenses a token from database')
             ->addOption(
                 'type',
                 't',
                 InputOption::VALUE_REQUIRED,
                 'Token type'
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
        /** @var string|null $type */
        $type = $input->getOption('type');

        return $type === null
            ? $this->messageAvailableTokens($output)
            : ($this->tokenTypeCollection->has($type)
                ? $this->dispenseToken($output, $type)
                : $this->messageTokenTypeNotSetup($output, $type)
            );
    }

    /**
     * @param OutputInterface $output
     * @param string          $type
     *
     * @return int
     */
    private function dispenseToken(OutputInterface $output, string $type): int
    {
        $availableToken = $this->tokenManager->available($type);

        return $availableToken->amount() === 0
            ? $this->messageTokenNotInStock($output, $type)
            : $this->createOrder($output, $availableToken);
    }

    /**
     * @param OutputInterface         $output
     * @param AvailableTokenInterface $availableToken
     *
     * @return int
     */
    private function createOrder(OutputInterface $output, AvailableTokenInterface $availableToken): int
    {
        $order = $this->orderManager->create($availableToken);

        return !$order->valid()
            ? $this->messageDispenseFailed($output)
            : $this->outputTokenAndClearOrder($output, $order);
    }

    /**
     * @param OutputInterface $output
     * @param                 $order
     *
     * @return int
     */
    private function outputTokenAndClearOrder(OutputInterface $output, Order $order): int
    {
        $token = $this->tokenManager->token($order->tokenId());
        $this->orderManager->clear($order);
        $output->writeln(
            sprintf(
                '<comment>Token dispensed: <info>%s (%s)</info></comment>',
                $token->value(),
                $token->type()->type()
            )
        );

        return 0;
    }

    /**
     * @param AvailableTokenInterface $availableToken
     *
     * @return string
     */
    private function formatAvailableToken(AvailableTokenInterface $availableToken): string
    {
        return sprintf(
            '<info>- %s (%s)</info>',
            $availableToken->type(),
            $availableToken->amount()
        );
    }

    /**
     * @param OutputInterface $output
     *
     * @return int
     */
    private function messageAvailableTokens(OutputInterface $output): int
    {
        $output->writeln(
            array_merge(
                ['<comment>Please select a token type with --type, currently available:</comment>'],
                array_map([$this, 'formatAvailableToken'], $this->tokenManager->allAvailable())
            )
        );

        return 0;
    }

    /**
     * @param OutputInterface $output
     * @param string          $type
     *
     * @return int
     */
    private function messageTokenTypeNotSetup(OutputInterface $output, string $type): int
    {
        $output->writeln(sprintf('<comment>Token type <info>%s</info> is not set up</comment>', $type));

        return 1;
    }

    /**
     * @param OutputInterface $output
     * @param string          $type
     *
     * @return int
     */
    private function messageTokenNotInStock(OutputInterface $output, string $type): int
    {
        $output->writeln(sprintf('<comment>No tokens of type <info>%s</info> currently in stock</comment>', $type));

        return 2;
    }

    /**
     * @param OutputInterface $output
     *
     * @return int
     */
    private function messageDispenseFailed(OutputInterface $output): int
    {
        $output->writeln('<error>Error during token dispense, please try again</error>');

        return 3;
    }
}
