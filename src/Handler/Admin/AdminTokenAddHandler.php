<?php

declare(strict_types=1);

namespace Okaruto\Space\Handler\Admin;

use Fig\Http\Message\RequestMethodInterface;
use Okaruto\Space\Business\Token\TokenManager;
use Okaruto\Space\Business\Token\TokenValidator;
use Okaruto\Space\Business\Token\Type\TokenTypeCollection;
use Okaruto\Space\Business\Token\Type\TokenTypeInterface;
use Okaruto\Space\Config\AdminConfig;
use Okaruto\Space\Renderer\RendererInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AdminTokenAddHandler
 *
 * @package   Okaruto\Space\Handler\Admin
 * @author    Okaruto Shirukoto <okaruto@protonmail.com>
 * @copyright Copyright (c) 2019, Okaruto Shirukoto
 * @license   http://opensource.org/licenses/MIT
 */
final class AdminTokenAddHandler
{

    /** @var AdminConfig */
    private $adminConfig;

    /** @var RendererInterface */
    private $renderer;

    /** @var TokenValidator */
    private $tokenValidator;

    /** @var TokenManager */
    private $tokenManager;

    /** @var TokenTypeCollection */
    private $tokenTypeCollection;

    /**
     * AdminTokenAddHandler constructor.
     *
     * @param AdminConfig         $adminConfig
     * @param RendererInterface   $renderer
     * @param TokenValidator      $tokenValidator
     * @param TokenManager        $tokenManager
     * @param TokenTypeCollection $tokenTypeCollection
     */
    public function __construct(
        AdminConfig $adminConfig,
        RendererInterface $renderer,
        TokenValidator $tokenValidator,
        TokenManager $tokenManager,
        TokenTypeCollection $tokenTypeCollection
    ) {
        $this->adminConfig = $adminConfig;
        $this->renderer = $renderer;
        $this->tokenValidator = $tokenValidator;
        $this->tokenManager = $tokenManager;
        $this->tokenTypeCollection = $tokenTypeCollection;
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

        $formStatus = [];
        $formSuccessMessages = [];
        $formErrorMessages = [];
        $formTextArea = [];

        if ($request->getMethod() === RequestMethodInterface::METHOD_POST) {
            $body = (array)$request->getParsedBody();

            if (!is_array($body) || !isset($body['key'], $body['tokens'])) {
                $formStatus[] = 'admin_form--failed';
                $formErrorMessages[] = 'Fill out all fields';
            } elseif ($this->adminConfig->key() !== $body['key']) {
                $formStatus[] = 'admin_form--failed';
                $formErrorMessages[] = 'Admin key invalid';
                $formTextArea = [$body['tokens']];
            } else {
                $result = $this->tokenManager->addTokens(explode(PHP_EOL, $body['tokens']));

                if (!empty($result['added'])) {
                    $formStatus[] = ' admin_form--succeeded';
                    $formSuccessMessages = array_merge(
                        $formSuccessMessages,
                        ['Tokens added'],
                        $result['added']
                    );
                }

                if (!empty($result['errors'])) {
                    $formStatus[] = ' admin_form--failed';
                    $formErrorMessages = ['Not all tokens could be added'];

                    if (!empty($result['errors']['mangled'])) {
                        $formErrorMessages = array_merge(
                            $formErrorMessages,
                            ['Type missing, row format example XXXXX-XXXXX-XXXXX-XXXXX;1month'],
                            $result['errors']['mangled']
                        );

                        $formTextArea = array_merge($formTextArea, $result['errors']['mangled']);
                    }

                    if (!empty($result['errors']['unavailable'])) {
                        $formErrorMessages = array_merge(
                            $formErrorMessages,
                            ['No price set or unsupported type'],
                            $result['errors']['unavailable']
                        );

                        $formTextArea = array_merge($formTextArea, $result['errors']['unavailable']);
                    }

                    if (!empty($result['errors']['duplicates'])) {
                        $formErrorMessages = array_merge(
                            $formErrorMessages,
                            ['Already stored'],
                            $result['errors']['duplicates']
                        );

                        $formTextArea = array_merge($formTextArea, $result['errors']['duplicates']);
                    }

                    if (!empty($result['errors']['format'])) {
                        $formErrorMessages = array_merge(
                            $formErrorMessages,
                            ['Not correctly formatted (XXXXX-XXXXX-XXXXX-XXXXX)'],
                            $result['errors']['format']
                        );

                        $formTextArea = array_merge($formTextArea, $result['errors']['format']);
                    }

                    if (!empty($result['errors']['invalid'])) {
                        $formErrorMessages = array_merge(
                            $formErrorMessages,
                            ['Invalid:'],
                            $result['errors']['invalid']
                        );

                        $formTextArea = array_merge($formTextArea, $result['errors']['invalid']);
                    }

                    if (!empty($result['errors']['spent'])) {
                        $formErrorMessages = array_merge(
                            $formErrorMessages,
                            ['These tokens are already being used:'],
                            $result['errors']['spent']
                        );

                        $formTextArea = array_merge($formTextArea, $result['errors']['spent']);
                    }

                    if (!empty($result['errors']['unknown'])) {
                        $formErrorMessages = array_merge(
                            $formErrorMessages,
                            ['These Tokens delivered an unknown verification result'],
                            $result['errors']['unknown']
                        );

                        $formTextArea = array_merge($formTextArea, $result['errors']['unknown']);
                    }
                }
            }
        }

        return $this->renderer->render(
            $response,
            'admin/token/add',
            [
                'autoTokenType' => $this->tokenValidator->verifies(),
                'formTextArea' => trim(join(PHP_EOL, $formTextArea)),
                'formStatus' => join(' ', $formStatus),
                'formSuccessMessage' => join('<br>', $formSuccessMessages),
                'formErrorMessage' => join('<br>', $formErrorMessages),
                'tokenTypes' => array_map(
                    function (TokenTypeInterface $type) {
                        return $type->type();
                    },
                    $this->tokenTypeCollection->available()
                ),
            ]
        );
    }
}
