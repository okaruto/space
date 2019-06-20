<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\PostParams;

use Okaruto\Space\PostParams\ContactFields;
use PHPUnit\Framework\TestCase;

/**
 * Class ContactFieldsTest
 *
 * @package   Okaruto\Space\Tests\Unit\PostParams
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class ContactFieldsTest extends TestCase
{

    public function testValidParams(): void
    {
        $contactFields = new ContactFields([
            'email' => 'unit@test.sender',
            'subject' => 'Unit Test',
            'message' => 'Just a unit test message',
        ]);

        $this->assertSame(true, $contactFields->valid());
        $this->assertSame('unit@test.sender', $contactFields->email());
        $this->assertSame('Unit Test', $contactFields->subject());
        $this->assertSame('Just a unit test message', $contactFields->message());
    }

    public function testMissingFields(): void
    {
        $contactFields = new ContactFields([
            'email' => 'unit@test.sender',
            'subject' => 'Unit Test',
        ]);

        $this->assertSame(false, $contactFields->valid());

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Trying to access invalid ContactFields');
        $contactFields->email();
    }

    public function testInvalidEmailField(): void
    {
        $contactFields = new ContactFields([
            'email' => 'unittest.sender',
            'subject' => 'Unit Test',
            'message' => 'Just a unit test message',
        ]);

        $this->assertSame(false, $contactFields->valid());

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Trying to access invalid ContactFields');
        $contactFields->email();
    }
}
