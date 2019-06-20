<?php

declare(strict_types=1);

namespace Okaruto\Space\Renderer;

use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface;

/**
 * Class PlatesRenderer
 *
 * @package   Okaruto\Space\Renderer
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class PlatesRenderer implements RendererInterface
{

    /** @var Engine */
    private $plates;

    /**
     * PlatesRenderer constructor.
     *
     * @param Engine $plates
     */
    public function __construct(Engine $plates)
    {
        $this->plates = $plates;
    }

    /**
     * @param ResponseInterface $response
     * @param string            $template
     * @param array             $data
     *
     * @return ResponseInterface
     */
    public function render(ResponseInterface $response, string $template, array $data = []): ResponseInterface
    {
        $response->getBody()->write($this->plates->render($template, $data));

        return $response;
    }
}
