<?php

declare(strict_types=1);

namespace Okaruto\Space\Tests\Unit\Renderer;

use League\Plates\Engine;
use Okaruto\Space\Renderer\PlatesRenderer;
use PHPUnit\Framework\TestCase;

/**
 * Class PlatesRendererTest
 *
 * @package   Okaruto\Space\Tests\Unit\Renderer
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
class PlatesRendererTest extends TestCase
{

    private const TEMPLATE_DIR = APPLICATION . '/data/unit_templates';
    private const TEMPLATE_FILE = self::TEMPLATE_DIR . '/template.phtml';

    public function setUp()
    {
        mkdir(self::TEMPLATE_DIR, 0777, true);
        file_put_contents(self::TEMPLATE_FILE, '# <?= $rendering; ?> #');
    }

    public function tearDown()
    {
        unlink(self::TEMPLATE_FILE);
        rmdir(self::TEMPLATE_DIR);
    }

    public function testRender(): void
    {
        $renderer = new PlatesRenderer(new Engine(self::TEMPLATE_DIR, 'phtml'));
        $response = new \Slim\Http\Response();

        $response = $renderer->render($response, 'template', ['rendering' => 'testRendering']);

        $this->assertSame(
            '# testRendering #',
            (string)$response->getBody()
        );
    }
}
