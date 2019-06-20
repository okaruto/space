<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Integration\Business\Invoice;

use Okaruto\Cryptonator\MerchantApi;
use Okaruto\Space\Business\Invoice\InvoiceManager;
use Okaruto\Space\Database\PDOSqliteMemory;
use Okaruto\Space\Tests\MigrateDatebaseTrait;
use Okaruto\Space\Tests\PrepareClientTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class InvoiceManagerTest
 *
 * @package   Okaruto\Space\Tests\Integration\Business\Invoice
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class InvoiceManagerTest extends TestCase
{

    use MigrateDatebaseTrait;
    use PrepareClientTrait;

    public function testInvoiceManager(): void
    {
        $this->markTestSkipped();

    }
}
