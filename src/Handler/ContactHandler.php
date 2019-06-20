<?php

declare(strict_types=1);

namespace Okaruto\Space\Handler;

use Lmc\HttpConstants\Header;
use Okaruto\Space\Mail\ContactMail;
use Okaruto\Space\Middleware\LanguageMandatoryMiddleware;
use Okaruto\Space\Middleware\XhrMiddleware;
use Okaruto\Space\PostParams\ContactFields;
use Okaruto\Space\Router\RouterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ContactHandler
 *
 * @package   Okaruto\Space\Handler
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class ContactHandler
{

    /** @var RouterInterface */
    private $router;

    /** @var ContactMail */
    private $contactMail;

    /**
     * ContactHandler constructor.
     *
     * @param RouterInterface $router
     * @param ContactMail     $contactMail
     */
    public function __construct(RouterInterface $router, ContactMail $contactMail)
    {
        $this->router = $router;
        $this->contactMail = $contactMail;
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

        $body = $request->getParsedBody();
        $success = $this->contactMail->send(new ContactFields((array)($body ?? [])));

        if ($request->getAttribute(XhrMiddleware::ATTRIBUTE) === true) {
            $response = $response->withStatus($success ? 200 : 400)
                                 ->withHeader(Header::CONTENT_TYPE, 'application/json');
        } else {
            $response = $this->router->redirect(
                $response,
                'index',
                303,
                [
                    'language' => $request->getAttribute(LanguageMandatoryMiddleware::ATTRIBUTE),
                ],
                [
                    'formStatus' => $success ? 'success' : 'fail',
                ]
            );
        }

        return $response;
    }
}
