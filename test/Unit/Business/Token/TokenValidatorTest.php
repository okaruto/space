<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Business\Token;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use Okaruto\Space\Business\Token\TokenValidationResult;
use Okaruto\Space\Business\Token\TokenValidator;
use Okaruto\Space\Config\AdminConfig;
use Okaruto\Space\Tests\PrepareClientTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class TokenValidatorTest
 *
 * @package   Okaruto\Space\Tests\Unit\Business\Token
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class TokenValidatorTest extends TestCase
{

    use PrepareClientTrait;

    private const TOKEN = 'unit0-test0-token-00000';
    private const TOKEN_HASH = 'f2c9d71ede3cef7679bfe04d55a4c224f7ae25955c802319c50e780a3006da5b0574a'
    . '8cac2259049b562662e5a40ebb21b0dd0723e4c8a984bb53050d485d2d5';

    public function testTokenValidator(): void
    {
        $requests = [];

        $validator = new TokenValidator(
            new AdminConfig([AdminConfig::KEY_KEY => 'key', AdminConfig::KEY_VERIFY_TOKENS => true]),
            $this->client([new Response()], $requests)
        );
        $result = $validator->validate('unit0-test0-token-00000');

        $this->assertInstanceOf(TokenValidationResult::class, $result);

        $this->assertSame(1, count($requests));

        /** @var Uri $uri */
        $uri = reset($requests)['request']->getUri();

        $this->assertSame('https', $uri->getScheme());
        $this->assertSame('cryptostorm.is', $uri->getHost());
        $this->assertSame('pubtokf?token=' . self::TOKEN_HASH, $uri->getQuery());
    }

    public function testTokenValidatorNoVerify(): void
    {
        $requests = [];

        $validator = new TokenValidator(
            new AdminConfig([AdminConfig::KEY_KEY => 'key', AdminConfig::KEY_VERIFY_TOKENS => false]),
            $this->client([new Response()], $requests)
        );

        $result = $validator->validate('unit0-test0-token-00000');

        $this->assertInstanceOf(TokenValidationResult::class, $result);

        $this->assertSame(0, count($requests));
    }
}
