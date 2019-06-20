<?php

declare(strict_types=1);

namespace Okaruto\Space\Renderer;

use League\Plates\Template\Template;
use Okaruto\Space\Container\LayoutVariables;
use Okaruto\Space\Renderer\Extensions\CurrencyExtension;
use Okaruto\Space\Renderer\Extensions\ExternalLinkExtension;
use Okaruto\Space\Renderer\Extensions\IconExtension;
use Okaruto\Space\Renderer\Extensions\InlineFileExtension;
use Okaruto\Space\Renderer\Extensions\LanguageSelectExtension;
use Okaruto\Space\Renderer\Extensions\LayoutVariablesExtension;
use Okaruto\Space\Renderer\Extensions\QrCodeExtension;
use Okaruto\Space\Renderer\Extensions\TranslatorExtension;
use Okaruto\Space\Renderer\Extensions\UrlExtension;

/**
 * Interface TemplateInterface
 *
 * @package   Okaruto\Space\Renderer
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 *
 * @see       Template::e()
 * @method    string e(string $string, ?string $functions = null)
 *
 * @see       Template::escape()
 * @method    string escape(string $string, ?string $functions = null)
 *
 * @see       Template::layout()
 * @method    void layout(string $name, array $data = [])
 *
 * @see       CurrencyExtension::_invoke()
 * @method    string currency(string $currency, float $value)
 *
 * @see       ExternalLinkExtension::__invoke()
 * @method    string externalLink(string $url, string $text)
 *
 * @see       IconExtension::_invoke()
 * @method    string icon(string $name, string $classes = '')
 *
 * @see       InlineFileExtension::__invoke()
 * @method    string inlineFile(string $relativePath)
 *
 * @see       LayoutVariablesExtension::_invoke()
 * @method    LayoutVariables lv()
 *
 * @see       QrCodeExtension::_invoke()
 * @method    string qrcode(string $content)
 *
 * @see       TranslatorExtension::_invoke()
 * @method    string t(string $key, $replacers = [])
 *
 * @see       UrlExtension::_invoke()
 * @method    string url(string $routeName, $routeArgs = [], $queryParams = [])
 *
 * @see       LanguageSelectExtension::_invoke()
 * @method    array languageSelect()
 */
interface TemplateInterface
{

}
