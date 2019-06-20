<?php

declare(strict_types=1);

use Okaruto\Cryptonator\Values\InvoiceCurrencyValue;
use Okaruto\Space\Business\CryptoCurrency;
use Okaruto\Space\Business\Token;
use Okaruto\Space\Config\AdminConfig;
use Okaruto\Space\Config\Config;
use Okaruto\Space\Config\CryptonatorConfig;
use Okaruto\Space\Config\LayoutConfig;
use Okaruto\Space\Config\MailConfig;
use Okaruto\Space\Config\MailIdentityConfig;
use Okaruto\Space\Config\TimeoutConfig;

return [
    Config::KEY_ADMIN => [
        AdminConfig::KEY_KEY => '', // Admin key for adding new tokens, please use a very long random string
        AdminConfig::KEY_VERIFY_TOKENS => true, // Should new tokens be checked against cryptostorm.nu for validity?
    ],
    Config::KEY_MAIL => [
        // Swift mailer transport to use for email sending, other transports may need a different dependency configuration
        MailConfig::KEY_TRANSPORT => \Swift_SmtpTransport::class,
        MailConfig::KEY_FROM => [
            MailIdentityConfig::KEY_EMAIL => 'name@example.com', // Sender address for system emails to you
            MailIdentityConfig::KEY_NAME => 'John Doe', // Sender name for system emails to you
        ],
        MailConfig::KEY_TO => [
            MailIdentityConfig::KEY_EMAIL => 'name@example.com', // Receiver address for system mails to you
            MailIdentityConfig::KEY_NAME => 'John Doe', // Receiver address for system mails to you
        ],
        // SMTP Server to send emails, only needed when transport => \Swift_SmtpTransport::class
        MailConfig::KEY_SERVER => 'mail.example.com',
        MailConfig::KEY_PORT => 25, // SMTP port
        MailConfig::KEY_USERNAME => null, // SMTP username
        MailConfig::KEY_PASSWORD => null, // SMTP password
    ],
    Config::KEY_TIMEOUTS => [
        // Timeouts are defined in PHP DateInterval specs:
        // @see: https://www.php.net/manual/en/dateinterval.construct.php
        // Cron delete timeout for orders without invoices (usually by API errors)
        TimeoutConfig::KEY_NEW => 'PT5M',
        // Cron delete timeout for expired orders
        TimeoutConfig::KEY_CANCELLED => 'PT15M',
        // Cron delete timeout for finished orders
        TimeoutConfig::KEY_PAID => 'PT30M',
    ],
    Config::KEY_CRYPTONATOR => [
        CryptonatorConfig::KEY_MERCHANT_ID => '',
        // Your cryptonator.com merchant id
        CryptonatorConfig::KEY_MERCHANT_SECRET => '',
        // Your cryptonator.com merchant secret
    ],
    Config::KEY_CURRENCY => InvoiceCurrencyValue::INVOICE_CURRENCY_USD, // Checkout currency
    Config::KEY_CRYPTO_CURRENCIES => [ // Your supported cryptocurrencies, you need to create wallet addresses on cryptonator.com to use them, remove all that you don't want to offer
        CryptoCurrency\Btc::class,
        CryptoCurrency\Bch::class,
        CryptoCurrency\Eth::class,
        CryptoCurrency\Etc::class,
        CryptoCurrency\Ltc::class,
        CryptoCurrency\Dash::class,
        CryptoCurrency\Doge::class,
        CryptoCurrency\Xmr::class,
        CryptoCurrency\Zec::class,
        CryptoCurrency\Bcn::class,
        CryptoCurrency\Ppc::class,
        CryptoCurrency\Emc::class,
    ],
    Config::KEY_TOKEN_TYPES => [ // Your token prices, best to set all of them so you can always add every token type
        Token\Type\OneWeek::class => 1.86,
        Token\Type\OneMonth::class => 6.00,
        Token\Type\ThreeMonths::class => 16.00,
        Token\Type\SixMonths::class => 28.00,
        Token\Type\OneYear::class => 52.00,
        Token\Type\TwoYears::class => 94.00,
        Token\Type\Lifetime::class => 500.00,
    ],
    Config::KEY_LAYOUT => [ // Stuff that people see in the page layout
        LayoutConfig::KEY_COMPANY => 'your awesome company name', // Name to show in header
        LayoutConfig::KEY_SLOGAN => 'independent cryptostorm.is token reseller', // Slogan shown in header
        LayoutConfig::KEY_TOR_DOMAIN => 'yourtordomain.onion', // Tor domain or false to disable output
        LayoutConfig::KEY_EMAIL => 'name@example.com', // Email address displayed on home page
        LayoutConfig::KEY_YEAR => (int)'2019', // Year of service start :)
        LayoutConfig::KEY_PUBLIC_KEY_ID => '0xAA1C397A8A70C6E9', // Your PGP/GPG public key id on pgp.mit.edu or false to disable OpenPGP.js encryption
        LayoutConfig::KEY_PUBLIC_KEY => <<<'EOT'
-----BEGIN PGP PUBLIC KEY BLOCK-----
your PGP/GPG public key comes in here
-----END PGP PUBLIC KEY BLOCK-----
EOT
    ],
];
