<?php

declare(strict_types=1);

namespace Okaruto\Space\Command;

use Okaruto\Cryptonator\Exceptions\WrongInvoiceTypeException;
use Okaruto\Space\Business\Invoice\InvoiceManager;
use Okaruto\Space\Business\Order\Order;
use Okaruto\Space\Business\Order\OrderManager;
use Okaruto\Space\Business\Token\Token;
use Okaruto\Space\Business\Token\TokenManager;
use Okaruto\Space\Interval\CancelledTimeoutInterval;
use Okaruto\Space\Interval\NewTimeoutInterval;
use Okaruto\Space\Interval\PaidTimeOutInterval;
use Okaruto\Space\Mail\HouseKeepingMail;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class HousekeepingCommand
 *
 * @package   Okaruto\Space\Command
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class HousekeepingCommand extends Command
{

    /** @var TokenManager */
    private $tokenManager;

    /** @var OrderManager */
    private $orderManager;

    /** @var InvoiceManager */
    private $invoiceManager;

    /** @var HousekeepingMail */
    private $housekeepingMail;

    /** @var \PDO */
    private $pdo;

    /** @var PaidTimeOutInterval */
    private $paidTimeOutInterval;

    /** @var CancelledTimeoutInterval */
    private $cancelledTimeoutInterval;

    /** @var NewTimeoutInterval */
    private $newTimeoutInterval;

    /** @var LoggerInterface */
    private $logger;

    /**
     * HousekeepingCommand constructor.
     *
     * @param \PDO                     $pdo
     * @param TokenManager             $tokenManager
     * @param OrderManager             $orderManager
     * @param InvoiceManager           $invoiceManager
     * @param PaidTimeOutInterval      $paidTimeOutInterval
     * @param CancelledTimeoutInterval $cancelledTimeoutInterval
     * @param NewTimeoutInterval       $newTimeoutInterval
     * @param LoggerInterface          $logger
     * @param HousekeepingMail         $houseKeepingMail
     */
    public function __construct(
        \PDO $pdo,
        TokenManager $tokenManager,
        OrderManager $orderManager,
        InvoiceManager $invoiceManager,
        PaidTimeOutInterval $paidTimeOutInterval,
        CancelledTimeoutInterval $cancelledTimeoutInterval,
        NewTimeoutInterval $newTimeoutInterval,
        HousekeepingMail $houseKeepingMail,
        LoggerInterface $logger
    ) {
        $this->pdo = $pdo;
        $this->tokenManager = $tokenManager;
        $this->orderManager = $orderManager;
        $this->invoiceManager = $invoiceManager;
        $this->paidTimeOutInterval = $paidTimeOutInterval;
        $this->cancelledTimeoutInterval = $cancelledTimeoutInterval;
        $this->newTimeoutInterval = $newTimeoutInterval;
        $this->housekeepingMail = $houseKeepingMail;
        $this->logger = $logger;

        parent::__construct();
    }

    /**
     * Configures the command
     */
    protected function configure()
    {
        $this->setName('cron:housekeeping')
             ->setDescription('Clears completed / cancelled order from database')
             ->addOption(
                 'mail',
                 'm',
                 InputOption::VALUE_NONE,
                 'Send mail'
             );
    }

    /**
     * Executes the current command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = [
            'forceRemove' => $this->processClearOrders(),
            'paid' => $this->processPaidInvoices(),
            'cancelled' => $this->processCancelledInvoices(),
            'withoutInvoice' => $this->processNewOrders(),
        ];

        if (!empty($data['forceRemove'])
            || !empty($data['paid'])
            || !empty($data['cancelled'])
            || !empty($data['withoutInvoice'])
        ) {
            $this->pdo->exec('VACUUM');
        }

        $data = array_filter($data);
        $message = !empty($data) ? json_encode($data, JSON_PRETTY_PRINT) : '';

        if (!empty($message)) {
            $output->writeln($message);

            if ($input->getOption('mail') === true) {
                $this->housekeepingMail->send($message);
            }
        }
    }

    /**
     * @param \DateTimeImmutable $timestamp
     * @param \DateInterval      $interval
     *
     * @return bool
     */
    private function timeoutReached(\DateTimeImmutable $timestamp, \DateInterval $interval): bool
    {
        return $timestamp->add($interval) < (new \DateTimeImmutable());
    }

    /**
     * @param Order $order
     * @param Token $token
     *
     * @return array
     */
    private function formatOrderMessage(Order $order, Token $token): array
    {
        return [
            'id' => $order->id(),
            'token' => $token->type()->type(),
            'price' => sprintf('%s %s', $order->price(), $order->currency()),
        ];
    }

    /**
     * @return array
     */
    private function processClearOrders(): array
    {
        $data = [];

        foreach ($this->orderManager->statusClear() as $forceClearOrder) {
            $token = $this->tokenManager->token($forceClearOrder->tokenId());

            if ($this->tokenManager->remove($token)) {
                $this->logToken($forceClearOrder, $token);
                array_push($data, $this->formatOrderMessage($forceClearOrder, $token));
            };
        }

        return $data;
    }

    /**
     * @return array
     */
    private function processPaidInvoices(): array
    {
        $data = [];

        foreach ($this->invoiceManager->statusPaid() as $paidInvoice) {
            $paidOrder = $this->orderManager->order($paidInvoice->orderId());

            $token = $this->tokenManager->token($paidOrder->tokenId());

            if ($this->timeoutReached($paidInvoice->dates()->dateTime(), $this->paidTimeOutInterval)
                && $this->tokenManager->remove($token)
            ) {
                $this->logToken($paidOrder, $token);
                array_push($data, $this->formatOrderMessage($paidOrder, $token));
            }
        }

        return $data;
    }

    /**
     * @return array
     */
    private function processCancelledInvoices(): array
    {
        $data = [];

        foreach ($this->invoiceManager->statusCancelled() as $cancelledInvoice) {
            $cancelledOrder = $this->orderManager->orderByInvoice($cancelledInvoice);

            if ($this->timeoutReached($cancelledInvoice->dates()->expires(), $this->cancelledTimeoutInterval)
                && $this->orderManager->remove($cancelledOrder)
            ) {
                array_push($data, $cancelledOrder->id());
            }
        }

        return $data;
    }

    /**
     * @return array
     */
    private function processNewOrders(): array
    {
        $data = [];

        foreach ($this->orderManager->statusNew() as $newOrder) {
            $invoice = $this->invoiceManager->invoice($newOrder);

            try {
                $invoice->valid();
            } catch (WrongInvoiceTypeException $exception) {
                if ($this->timeoutReached($newOrder->created(), $this->newTimeoutInterval)
                    && $this->orderManager->remove($newOrder)
                ) {
                    array_push($data, $newOrder->id());
                }
            }
        }

        return $data;
    }

    /**
     * @param Order $order
     * @param Token $token
     *
     * @return void
     */
    private function logToken(Order $order, Token $token): void
    {
        $this->logger->info(
            sprintf(
                'Order %s with %s token %s cleared.',
                $order->id(),
                $token->type()->type(),
                $token->value()
            )
        );
    }
}
