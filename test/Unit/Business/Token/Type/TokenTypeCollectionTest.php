<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Business\Token\Type;

use Okaruto\Space\Business\Token\Type\Lifetime;
use Okaruto\Space\Business\Token\Type\OneMonth;
use Okaruto\Space\Business\Token\Type\OneYear;
use Okaruto\Space\Business\Token\Type\SixMonths;
use Okaruto\Space\Business\Token\Type\ThreeMonths;
use Okaruto\Space\Business\Token\Type\TokenTypeCollection;
use Okaruto\Space\Tests\ContainerTrait;
use Okaruto\Space\Translation\TranslationContainer;
use PHPUnit\Framework\TestCase;
use Zalas\Injector\PHPUnit\TestCase\ServiceContainerTestCase;

/**
 * Class TokenTypeCollectionTest
 *
 * @package   Okaruto\Space\Tests\Unit\Business\Token\Type
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class TokenTypeCollectionTest extends TestCase implements ServiceContainerTestCase
{

    use ContainerTrait;

    /**
     * @var TranslationContainer
     * @inject
     */
    private $translationContainer;

    public function testCollectionAvailable(): void
    {
        $collection = new TokenTypeCollection(
            $this->translationContainer,
            [
                OneMonth::class => 1.11,
                ThreeMonths::class => 3.33,
                SixMonths::class => 6.66,
            ],
            'usd'
        );

        $available = $collection->available();

        $this->assertInstanceOf(SixMonths::class, array_pop($available));
        $this->assertInstanceOf(ThreeMonths::class, array_pop($available));
        $this->assertInstanceOf(OneMonth::class, array_pop($available));
    }

    public function testCollectionMissingPrice(): void
    {
        $collection = new TokenTypeCollection(
            $this->translationContainer,
            [
                OneMonth::class => null,
                OneYear::class => 12.12,
            ],
            'usd'
        );

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Price for token type Okaruto\Space\Business\Token\Type\OneMonth not set');
        $collection->available();
    }

    public function testCollectionHas(): void
    {
        $collection = new TokenTypeCollection(
            $this->translationContainer,
            [
                OneMonth::class => 1.11,
                ThreeMonths::class => 3.33,
                SixMonths::class => 6.66,
            ],
            'usd'
        );

        $this->assertSame(true, $collection->has(OneMonth::TYPE));
        $this->assertSame(false, $collection->has(Lifetime::TYPE));
    }

    public function testCollectionGet(): void
    {
        $collection = new TokenTypeCollection(
            $this->translationContainer,
            [
                OneMonth::class => 1.11,
                ThreeMonths::class => 3.33,
                SixMonths::class => 6.66,
            ],
            'usd'
        );

        $this->assertInstanceOf(OneMonth::class, $collection->get(OneMonth::TYPE));

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Trying to retrieve undefiend token type: lifetime');
        $collection->get(Lifetime::TYPE);

    }
}
