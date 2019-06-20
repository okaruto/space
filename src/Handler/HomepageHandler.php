<?php

declare(strict_types=1);

namespace Okaruto\Space\Handler;

use Okaruto\Space\Business\CryptoCurrency\CryptoCurrencyCollection;
use Okaruto\Space\Business\Token\TokenManager;
use Okaruto\Space\Renderer\RendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class HomepageHandler
 *
 * @package   Okaruto\Space\Handler
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class HomepageHandler
{

    const FORM_STATUS_SUCCESS = 'success';
    const FORM_STATUS_FAIL = 'fail';

    const FORM_STATI = [
        self::FORM_STATUS_SUCCESS => 'form--succeeded',
        self::FORM_STATUS_FAIL => 'form--failed',
    ];

    /** @var RendererInterface */
    private $renderer;

    /** @var CryptoCurrencyCollection */
    private $cryptoCurrencyCollection;

    /** @var  */
    private $tokenManager;

    /**
     * HomepageHandler constructor.
     *
     * @param RendererInterface        $renderer
     * @param CryptoCurrencyCollection $cryptoCurrencyCollection
     * @param TokenManager             $tokenManager
     */
    public function __construct(
        RendererInterface $renderer,
        CryptoCurrencyCollection $cryptoCurrencyCollection,
        TokenManager $tokenManager
    ) {
        $this->renderer = $renderer;
        $this->cryptoCurrencyCollection = $cryptoCurrencyCollection;
        $this->tokenManager = $tokenManager;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @return ResponseInterface
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        $formStatus = $request->getQueryParams()['formStatus'] ?? null;

        return $this->renderer->render($response, 'homepage/index', [
            'formStatus' => $formStatus !== null && isset(self::FORM_STATI[$formStatus])
                ? self::FORM_STATI[$formStatus]
                : '',
            'cryptoCurrencies' => $this->cryptoCurrencyCollection->available(),
            'availableTokens' => $this->tokenManager->allAvailable(),
        ]);
    }
}
