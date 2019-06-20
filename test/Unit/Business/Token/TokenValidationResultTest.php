<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Business\Token;

use Okaruto\Space\Business\Token\TokenValidationResult;
use PHPUnit\Framework\TestCase;

/**
 * Class TokenValidationResultTest
 *
 * @package   Okaruto\Space\Tests\Unit\Business\Token
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class TokenValidationResultTest extends TestCase
{

    private const VALID_HTML = <<<'EOT'
<html><body><div style="text-align: center;"></div><div style="text-align: center;">
That token is VALID and has not yet been used. It will expire %s days after first use.
</div></body></html>
EOT;

    public function testValidResult(): void
    {
        $result = new TokenValidationResult(
            true,
            'unit0-test0-token-00000',
            <<<'EOT'
<html><body><div style="text-align: center;"></div><div style="text-align: center;">
That token is VALID and has not yet been used. It will expire 31 days after first use.
</div></body></html>
EOT
        );

        $this->assertSame(true, $result->valid());
    }

    public function testValidResultTypeDetection(): void
    {
        $value = 'unit0-test0-token-00000';

        foreach (
            [
                7 => '1week',
                31 => '1month',
                90 => '3months',
                183 => '6months',
                365 => '1year',
                730 => '2years',
                22000 => 'lifetime',
            ]
            as $days => $type
        ) {

            $result = new TokenValidationResult(
                true,
                $value,
                sprintf(self::VALID_HTML, $days)
            );

            $this->assertSame($type, $result->type());

        }

    }

    public function testUnverifiedTypeAccess(): void
    {
        $result = new TokenValidationResult(
            false,
            'unit0-test0-token-00000',
            sprintf(self::VALID_HTML, 7)
        );

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Trying to read token type from unverified validation result');
        $result->type();
    }

    public function testInvalidResult(): void
    {
        $result = new TokenValidationResult(
            true,
            'unit0-test0-token-00000',
            <<<'EOT'
<html><body><div style="text-align: center;"></div><div style="text-align: center;">
Invalid token.
</div></body></html>
EOT
        );

        $this->assertSame(false, $result->valid());
        $this->assertSame(true, $result->reasonTokenInvalid());

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Trying to access invalid validation result');
        $result->type();
    }

    public function testValidSpentResult(): void
    {
        $result = new TokenValidationResult(
            true,
            'unit0-test0-token-00000',
            <<<'EOT'
<html><body><div style="text-align: center;"></div><div style="text-align: center;">
token is VALID and will expire in 666 days
</div></body></html>
EOT
        );

        $this->assertSame(false, $result->valid());
        $this->assertSame(true, $result->reasonTokenSpent());
    }

    public function testUnknownResult(): void
    {
        $result = new TokenValidationResult(
            true,
            'unit0-test0-token-00000',
            <<<'EOT'
<html><body></body></html>
EOT
        );

        $this->assertSame(false, $result->valid());
        $this->assertSame(true, $result->reasonUnkown());
    }

    public function testValidResultNoVerify(): void
    {
        $result = new TokenValidationResult(false, 'unit0-test0-token-00000', '');

        $this->assertSame(true, $result->valid());
    }

    public function testInvalidResultNoVerify(): void
    {
        $result = new TokenValidationResult(false, 'wrong-formatted-token', '');

        $this->assertSame(false, $result->valid());
        $this->assertSame(true, $result->reasonFormatInvalid());
    }

    public function testValidValidationNoVerifyReasonCode(): void
    {
        $result = new TokenValidationResult(false, 'unit0-test0-token-00000', '');

        $this->assertSame(TokenValidationResult::REASON_VALID, $result->reasonCode());
    }

    public function testUnknownTypeDetected(): void
    {
        $result = new TokenValidationResult(
            true,
            'unit0-test0-token-00000',
            sprintf(self::VALID_HTML, 1024)
        );

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Trying to access invalid validation result');
        $result->type();
    }

}
