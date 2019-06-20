<?php

declare(strict_types=1);

/** @var $container \League\Container\Container */

// DIC configuration
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use League\Plates\Engine;
use Negotiation\Negotiator;
use Okaruto\Cryptonator\MerchantApi;
use Okaruto\Cryptonator\MerchantApiInterface;
use Okaruto\Space\Business\CryptoCurrency\CryptoCurrencyCollection;
use Okaruto\Space\Business\Token\Type\TokenTypeCollection;
use Okaruto\Space\Config\AdminConfig;
use Okaruto\Space\Config\Config;
use Okaruto\Space\Config\CryptoCurrencyConfig;
use Okaruto\Space\Config\CryptonatorConfig;
use Okaruto\Space\Config\LayoutConfig;
use Okaruto\Space\Config\MailConfig;
use Okaruto\Space\Config\TimeoutConfig;
use Okaruto\Space\Config\TokenTypeConfig;
use Okaruto\Space\Container\LayoutVariables;
use Okaruto\Space\Database;
use Okaruto\Space\Handler\ErrorHandler;
use Okaruto\Space\Handler\NotFoundHandler;
use Okaruto\Space\Image\Svg;
use Okaruto\Space\Interval\CancelledTimeoutInterval;
use Okaruto\Space\Interval\NewTimeoutInterval;
use Okaruto\Space\Interval\PaidTimeOutInterval;
use Okaruto\Space\Opcache\Opcache;
use Okaruto\Space\Opcache\OpcacheInterface;
use Okaruto\Space\QrCode\QrCode;
use Okaruto\Space\Renderer\Extensions\CurrencyExtension;
use Okaruto\Space\Renderer\Extensions\ExternalLinkExtension;
use Okaruto\Space\Renderer\Extensions\IconExtension;
use Okaruto\Space\Renderer\Extensions\InlineFileExtension;
use Okaruto\Space\Renderer\Extensions\LanguageSelectExtension;
use Okaruto\Space\Renderer\Extensions\LayoutVariablesExtension;
use Okaruto\Space\Renderer\Extensions\QrCodeExtension;
use Okaruto\Space\Renderer\Extensions\TranslatorExtension;
use Okaruto\Space\Renderer\Extensions\UrlExtension;
use Okaruto\Space\Renderer\PlatesRenderer;
use Okaruto\Space\Renderer\RendererInterface;
use Okaruto\Space\Router\RouterInterface;
use Okaruto\Space\Router\SlimRouterAdapter;
use Okaruto\Space\Translation\AvailableLocales;
use Okaruto\Space\Translation\TranslationContainer;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\CallableResolver;

$container->share(ContainerInterface::class, function () use ($container) {
    return $container;
});

// Callable resolver ftw!
$container->share('callableResolver', function () use ($container) {
    return $container->get(CallableResolver::class);
});

// Error handling
$container->share('phpErrorHandler', function () use ($container): ErrorHandler {

    $displayErrorDetails = $container->get('settings')['displayErrorDetails'];

    return new ErrorHandler(
        $displayErrorDetails,
        new \Slim\Handlers\PhpError($displayErrorDetails),
        $container->get(Negotiator::class),
        $container->get(RendererInterface::class),
        $container->get(LoggerInterface::class)
    );

});

$container->share('errorHandler', function () use ($container) {
    return $container->get('phpErrorHandler');
});

$container->share('notFoundHandler', function () use ($container): NotFoundHandler {
    return $container->get(NotFoundHandler::class);
});

// Config
$container->share(Config::class, function () use ($container): Config {
    return new Config($container->get('space'));
});

$container->share(AdminConfig::class, function () use ($container): AdminConfig {
    return $container->get(Config::class)->admin();
});

$container->share(MailConfig::class, function () use ($container): MailConfig {
    return $container->get(Config::class)->mail();
});

$container->share(TimeoutConfig::class, function () use ($container): TimeoutConfig {
    return $container->get(Config::class)->timeouts();
});

$container->share(CryptonatorConfig::class, function () use ($container): CryptonatorConfig {
    return $container->get(Config::class)->cryptonator();
});

$container->share(CryptoCurrencyConfig::class, function () use ($container): CryptoCurrencyConfig {
    return $container->get(Config::class)->cryptoCurrencies();
});

$container->share(TokenTypeConfig::class, function () use ($container): TokenTypeConfig {
    return $container->get(Config::class)->tokenTypes();
});

$container->share(LayoutConfig::class, function () use ($container): LayoutConfig {
    return $container->get(Config::class)->layout();
});

// Rendering
$container->share(OpcacheInterface::class, function () use ($container): OpcacheInterface {

    return new Opcache(
        $container->get('settings')['opcache'] ?? null,
        APPLICATION . '/ui'
    );

});

$container->share(IconExtension::class, function () use ($container): IconExtension {
    return new IconExtension(
        new Svg('images/fontawesome', $container->get(OpcacheInterface::class))
    );

});

$container->share(LanguageSelectExtension::class, function() use ($container): LanguageSelectExtension {
    return new LanguageSelectExtension(
        new Svg('images/flags', $container->get(OpcacheInterface::class)),
        $container->get(AvailableLocales::class),
        $container->get(RouterInterface::class),
        $container->get(LayoutVariables::class)
    );
});

$container->share(RendererInterface::class, function () use ($container): RendererInterface {

    $settings = $container->get('settings')['renderer'];

    $engine = new Engine($settings['template_path'] . DIRECTORY_SEPARATOR, $settings['template_extension']);

    $engine->loadExtensions([
        $container->get(TranslatorExtension::class),
        $container->get(LayoutVariablesExtension::class),
        $container->get(UrlExtension::class),
        $container->get(CurrencyExtension::class),
        $container->get(QrCodeExtension::class),
        $container->get(IconExtension::class),
        $container->get(ExternalLinkExtension::class),
        $container->get(InlineFileExtension::class),
        $container->get(LanguageSelectExtension::class),
    ]);

    return new PlatesRenderer($engine);
});

// Logger
$container->share(LoggerInterface::class, function () use ($container): LoggerInterface {
    $settings = $container->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));

    return $logger;
});

$container->share('logger', LoggerInterface::class);

// Router
$container->share(\Slim\Interfaces\RouterInterface::class, function () use ($container) {
    return $container->get('router');
});

$container->share(RouterInterface::class, SlimRouterAdapter::class)
          ->addArgument(\Slim\Interfaces\RouterInterface::class);

// Translation
$container->share(AvailableLocales::class, function () use ($container): AvailableLocales {
    $settings = $container->get('settings')['translation'];

    return new AvailableLocales($settings['path'], $settings['fallback']);
});

// Not found handler
$container->share(\Slim\Handlers\NotFound::class, function () use ($container) {
    return $container->get('notFoundHandler');
});

// Translation
$container->share(TranslationContainer::class, function () use ($container): TranslationContainer {

    $availableLocales = $container->get(AvailableLocales::class);
    $translationContainer = new TranslationContainer($availableLocales);
    $translationContainer->setTranslation($availableLocales->fallbackLocale());

    return $translationContainer;
});

// Cryptocurrencies
$container->share(CryptoCurrencyCollection::class, function () use ($container): CryptoCurrencyCollection {

    return new CryptoCurrencyCollection(
        new Svg('images/cryptocoins', $container->get(OpcacheInterface::class)),
        $container->get(CryptoCurrencyConfig::class)
    );

});

// Database

$container->share(Database\PDOSqliteFile::class, function () use ($container): Database\PDOSqliteFile {
    return new Database\PDOSqliteFile($container->get('settings')['database']['file']);
});

$container->share(\PDO::class, function () use ($container) {
    return $container->get(Database\PDOSqliteFile::class);
});

// QR codes

$container->share(QrCode::class, function () use ($container): QrCode {

    $renderer = new \BaconQrCode\Renderer\Image\Svg();
    $renderer->setHeight(128);
    $renderer->setWidth(128);
    $renderer->setMargin(0);

    return new QrCode(new \BaconQrCode\Writer($renderer));

});

// Tokens

$container->share(TokenTypeCollection::class, function () use ($container): TokenTypeCollection {

    return new TokenTypeCollection(
        $container->get(TranslationContainer::class),
        $container->get(TokenTypeConfig::class)->all(),
        $container->get(Config::class)->currency()->value()
    );
});

// Mailing

$container->share(\Swift_SmtpTransport::class, function () use ($container) {

    /** @var MailConfig $config */
    $config = $container->get(MailConfig::class);

    $transport = new \Swift_SmtpTransport($config->server(), $config->port());

    if ($config->usernameAndPasswordAvailable()) {

        $transport->setUsername($config->username())
                  ->setPassword($config->password());

    }

    return $transport;
});

$container->share(Swift_Transport::class, function () use ($container) {
    return $container->get($container->get(MailConfig::class)->transport());
});

$container->share(Swift_Mailer::class, function () use ($container) {
    return new Swift_Mailer($container->get(Swift_Transport::class));
});

$container->add(Swift_Message::class, function () use ($container) {

    /** @var MailConfig $config */
    $config = $container->get(MailConfig::class);

    $message = new Swift_Message();
    $config->from($message);
    $config->to($message);

    return $message;
}, false);

// HTTP Client

$container->add(ClientInterface::class, function () use ($container) {

    return new Client(
        [
            RequestOptions::VERIFY => true,
        ]
    );

}, false);

// Cryptonator Merchant API

$container->share(MerchantApiInterface::class, function () use ($container): MerchantApiInterface {

    /** @var CryptonatorConfig $config */
    $config = $container->get(CryptonatorConfig::class);

    return new MerchantApi(
        $container->get(ClientInterface::class),
        $config->merchantId(),
        $config->merchantSecret()
    );
});

// Timeouts

$container->share(NewTimeoutInterval::class, function () use ($container): NewTimeoutInterval {
    return $container->get(TimeoutConfig::class)->new();
});

$container->share(CancelledTimeoutInterval::class, function () use ($container): CancelledTimeoutInterval {
    return $container->get(TimeoutConfig::class)->cancelled();
});

$container->share(PaidTimeOutInterval::class, function () use ($container): PaidTimeOutInterval {
    return $container->get(TimeoutConfig::class)->paid();
});

// Load development dependencies
!file_exists(__DIR__ . '/dependencies.dev.php') ?: require(__DIR__ . '/dependencies.dev.php');
