<?php

declare(strict_types=1);

namespace Okaruto\Space\Renderer;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface RendererInterface
 *
 * @package   Okaruto\Space\Renderer
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
interface RendererInterface
{

    /**
     * @param ResponseInterface $response
     * @param string            $template
     * @param array             $data
     *
     * @return ResponseInterface
     */
    public function render(ResponseInterface $response, string $template, array $data = []): ResponseInterface;
}
