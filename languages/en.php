<?php

declare(strict_types=1);

return [
    'layout:link:contact' => 'contact',
    'layout:link:cryptostorm' => 'cryptostorm links',
    'layout:text:contentstolenwithpride' => 'content stolen with pride from',
    'layout:text:and' => 'and',

    'token:name:oneweek' => '1 week',
    'token:name:onemonth' => '1 month',
    'token:name:threemonths' => '3 months',
    'token:name:sixmonths' => '6 months',
    'token:name:oneyear' => '1 year',
    'token:name:twoyears' => '2 years',
    'token:name:lifetime' => 'Lifetime',

    'error:text:oops' => 'Oops! This is awkward.',
    'error:text:reason' => [
        'text' => 'We encountered a problem called "%s".',
        'replacers' => ['reason'],
    ],
    'error:text:404' => [
        'text' => 'You are looking for something that does not exist or may have moved. Check out one of the links on
                   this page or head back to %s.',
        'replacers' => ['home'],
    ],
    'error:link:home' => 'Home',

    'index:cryptostorm:text:catchphrase' => 'cryptostorm? secure, anonymous, affordable, vpn access',
    'index:cryptostorm:text:connections' => [
        'text' => '%s via %s or %s.',
        'replacers' => ['easyconnect', 'windowswidget', 'openvpn']
    ],
    'index:cryptostorm:link:easytoconnect' => 'Easy to connect',
    'index:cryptostorm:link:windowswidget' => 'Windows Widget',
    'index:cryptostorm:link:openvpn' => 'OpenVPN',
    'index:cryptostorm:text:adjectives' => 'VPN service, structurally anonymous, token-based, open-source, 50+ megabit/sec sessions service.',
    'index:cryptostorm:text:organization' => 'Rooted in Iceland, financials via Québec/First Nations territories, designed ground-up to embody a decentralised, flexible, crafty approach to real-world network security.',
    'index:cryptostorm:text:members' => 'Member security first; everything else is just backstory.',
    'index:cryptostorm:text:nologs' => [
        'text' => 'No logs - %s. Never betray network members.',
        'replacers' => ['seppuku'],
    ],
    'index:cryptostorm:text:tokenbasedauth' => [
        'text' => '%s - a real disconnect between customer and authentication.',
        'replacers' => ['tokenbased'],
    ],
    'index:cryptostorm:link:tokenbasedauth' => 'Token based authentication',
    'index:cryptostorm:headline:genuine' => 'Genuine',
    'index:cryptostorm:text:genuine' => 'No hypeware or marketing gimmicks. Open-source, published, peer-reviewed fundamentals. Dedicated servers, FDE from the metal. No-bullshit Terms of Service & Privacy Policy.',
    'index:cryptostorm:headline:strongcrypto' => 'Strong Crypto',
    'index:cryptostorm:text:strongcrypto' => 'From algorithm choice to OpSec procedures, they have been iteratively hardening attack surfaces for years. No encraption, broken protocols, or slapdash administration.',
    'index:cryptostorm:headline:battletested' => 'Battle tested',
    'index:cryptostorm:text:battletested' => 'Cryptostorm\'s core team helped launch the VPN industry, they have been redefining the possible every step since. Activist friendly? Indeed: they have directly supported info dissidents... for decades.',

    'index:reseller:text:indepedentcsreseller' => 'independent cryptostorm token reseller',
    'index:reseller:text:bulkpurchase' => 'We purchase tokens in bulk from cryptostorm and pass the additional anonymity on to you.',
    'index:reseller:text:noassociation' => [
        'text' => '%s is in no way associated with cryptostorm, simply another user of the vpn service.',
        'replacers' => ['name'],
    ],
    'index:reseller:text:noknowledge' => [
        'text' => 'Purchasing tokens via %s means cryptostorm cannot and does not know anything about you - no link between a token &amp; purchase details.',
        'replacers' => ['name'],
    ],
    'index:reseller:text:paymentprovider' => [
        'text' => 'Multiple cryptocoins for payment available, as payment provider we use %s.',
        'replacers' => ['link'],
    ],
    'index:reseller:text:noemailneeded' => 'No email needed for payment, just bookmark the payment page.',
    'index:reseller:text:tor' => [
        'text' => 'For even more anonymity, you can reach us on TOR too: %s.',
        'replacers' => ['domain'],
    ],
    'index:reseller:text:nojscookies' => 'Page works with JavaScript disabled, no sessions &amp; no cookies!',
    'index:reseller:text:transactions' => 'Transaction details get cleared shortly after transaction is complete.',
    'index:reseller:text:qrcode' => 'Additionally to token you get a QR code on the paid page pre-hashed for easy mobile scanning.',
    'index:reseller:headline:step1' => 'Step 1 - Purchase Token',
    'index:reseller:text:available' => 'Available tokens:',
    'index:reseller:text:instock' => 'in stock',
    'index:reseller:text:outofstock' => [
        'text' => 'currently out of stock %s please check back later',
        'replacers' => ['break'],
    ],
    'index:reseller:text:showhidecurrencies' => [
        'text' => '%s %s supported cryptocurrencies',
        'replacers' => ['show', 'hide'],
    ],
    'index:reseller:text:show' => 'Show',
    'index:reseller:text:hide' => 'Hide',
    'index:reseller:headline:step2' => 'Step 2 - Hash token',
    'index:reseller:text:receiveandverify' => [
        'text' => 'Receive network token and %s it.',
        'replacers' => ['link'],
    ],
    'index:reseller:text:verify' => 'verify',
    'index:reseller:text:calculatehash' => [
        'text' => 'Paste your network token into the %s to generate your Username.',
        'replacers' => ['link']
    ],
    'index:reseller:text:calculator' => 'sha512 calculator',
    'index:reseller:text:nopassword' => 'There is no password, you can enter whatever you want.',
    'index:reseller:headline:step3' => 'Step 3 - Connect to cryptostorm',
    'index:reseller:text:trywidget' => [
        'text' =>'Try the easy to use %s.',
        'replacers' => ['link'],
    ],
    'index:reseller:text:widget' => 'Windows Widget',
    'index:reseller:text:otheroses' => [
        'text' => '%s for Linux, Windows, Android, iOS and Mac.',
        'replacers' => ['link'],
    ],
    'index:reseller:text:connectionguides' => 'Detailed connection guides',
    'index:reseller:text:openvpn' => [
        'text' => 'You can always use the OpenVPN %s.',
        'replacers' => ['link'],
    ],
    'index:reseller:text:configurationfiles' => 'Configuration files',
    'index:contact:headline' => [
        'text' => 'Questions or suggestions? We %s to hear from you!',
        'replacers' => ['heart'],
    ],

    'index:contact:text:showhidepublickey' => [
        'text' => '%s %s Public PGP/GPG key',
        'replacers' => ['show', 'hide'],
    ],
    'index:contact:text:show' => 'Show',
    'index:contact:text:hide' => 'Hide',
    'index:contact:text:encrypted' => 'This form is secured with OpenPGP.js, a JavaScript implementation of the OpenPGP standard, so your message can only be decrypted by us.',
    'index:contact:text:unencrypted' => 'Please note that OpenPGP.js doesn\'t work when JavaScript is disabled. You can manually encrypt your message before sending by using our public key.',
    'index:contact:form:email' => 'Your email',
    'index:contact:form:emailplaceholder' => 'your@email.com',
    'index:contact:form:subject' => 'Subject',
    'index:contact:form:subjectplaceholder' => 'Whats up?',
    'index:contact:form:message' => 'Your message',
    'index:contact:form:messageplaceholder' => 'So it was like...',
    'index:contact:form:submit' => 'Submit message',
    'index:contact:form:success' => 'Thank you for your message, we will respond to you as soon as possible.',
    'index:contact:form:fail' => 'We are sorry, there was an error submitting your message. Please try again later.',

    'order:create:headline:purchasetoken' => 'Purchase Token',
    'order:create:select:choosecurrency' => 'Choose checkout cryptocurrency',
    'order:create:button:order' => [
        'text' => 'Order %s (%s)',
        'replacers' => ['name', 'price'],
    ],
    'order:create:text:error' => 'We are sorry, there was an error submitting your order. Please try again later.',
    'order:create:text:currencyunavailable' => 'We are sorry, the cryptocurrency you selected is temporarily not available. Please choose another.',
    'order:create:text:pleasewait' => 'Please wait after clicking the order button as the payment address is getting generated (this process can take multiple seconds).',
    'order:create:text:redirect' => 'You will automatically be redirected to the payment page, then you have about 30 minutes to make the cryptocoin transaction.',
    'order:create:text:tokensoutofstock' => [
        'text' => 'We are sorry, all tokens of this type (%s) are currently reserved or out of stock. Please try again later.',
        'replacers' => ['name'],
    ],

    'order:cancelled:text:description' => 'You did not send the payment in time so your order was cancelled. If you just sent it a bit too late there still is a chance that the transaction will go through and your order will be updated to paid status.',

    'order:clear:headline' => 'Clear.',
    'order:clear:description' => 'The records of your order have been marked for removal.',

    'order:confirming:headline' => [
        'text' => '%s - Confirming your transaction',
        'replacers' => ['name'],
    ],
    'order:confirming:text:description' => 'Your cryptocoin transfer was found but needs more confirmations to secure the transaction.',

    'order:mispaid:text:description' => 'You did not send the exact amount of cryptocurrency so the system rejected your payment.',

    'order:unpaid:text:description' => 'You can scan this QR code with your mobile wallet app to make a payment, or send exactly %s to this address',
    'order:unpaid:text:timeout' => 'You have %s minutes to complete this payment.',
    'order:unpaid:button:openinwallet' => 'Open in wallet',
    'order:unpaid:button:copyaddress' => 'Copy address',

    'order:paid:text:yourtoken' => 'Your token is',
    'order:paid:button:copytoken' => 'Copy token',
    'order:paid:text:qrcode' => 'You can scan the QR code to get the already hashed token username on your mobile phone.',
    'order:paid:text:info' => 'Your token is all you use to connect to cryptostorm. There is no "account" & no extra info.  Do not lose your token, as we cannot replace it - we delete records of sold tokens, permanently. And do not worry about using your token right away - it will not begin "eroding" towards expiration until you auth into cryptostorm the first time.',
    'order:paid:text:remotetimeout' => 'This record will be automatically marked for removal in %s minutes. We suggest you remove it earlier.',
    'order:paid:button:remove' => 'Remove this record now',

    'order:text:support' => [
        'text' => 'If you think there was an error, please contact us with your order id # %s so we can resolve this issue.',
        'replacers' => ['orderId'],
    ],
    'order:text:bookmark' => [
        'text' => 'Bookmark this page as we do not work with e-mail addresses. In case of problems, please give your order # %s so we can check your order.',
        'replacers' => ['orderId'],
    ],
    'order:text:pleaserefresh' => 'As Javascript is not enabled please refresh the page regularly to see status changes.',
    'order:html:backtohome' => 'back to main page',
];
