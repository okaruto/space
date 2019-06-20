<?php

declare(strict_types=1);

return [
    'layout:link:contact' => 'Обратная связь',
    'layout:link:cryptostorm' => 'Cryptostorm ссылки',
    'layout:text:contentstolenwithpride' => 'Содержание гордо позаимствовано с',
    'layout:text:and' => 'и',

    'token:name:oneweek' => '1 неделя',
    'token:name:onemonth' => '1 месяц',
    'token:name:threemonths' => '3 месяца',
    'token:name:sixmonths' => '6 месяцев',
    'token:name:oneyear' => '1 год',
    'token:name:twoyears' => '2 года',
    'token:name:lifetime' => 'Бесконечно',

    'error:text:oops' => 'Ой. Мне жаль.',
    'error:text:reason' => [
        'text' => 'Произошла ошибка (%s).',
        'replacers' => ['reason'],
    ],
    'error:text:404' => [
        'text' => 'Вы ищете то, что не существует или то что уже переехало. Взгляните на одну из ссылок на этой странице или вернитесь к $s.',
        'replacers' => ['home'],
    ],
    'error:link:home' => 'Главная',

    'index:cryptostorm:text:catchphrase' => 'Cryptostorm. Безопасный, анонимный, доступный, VPN-доступ',
    'index:cryptostorm:text:connections' => [
        'text' => '%s через %s или %s',
        'replacers' => ['easyconnect', 'windowswidget', 'openvpn']
    ],
    'index:cryptostorm:link:easytoconnect' => 'Простое подключение',
    'index:cryptostorm:link:windowswidget' => 'Windows Widget',
    'index:cryptostorm:link:openvpn' => 'OpenVPN',
    'index:cryptostorm:text:adjectives' => 'VPN-служба, анонимная по своей структуре, работающая на токенах, имеет открытый исходный код и скорость 50 Мегабит / сек.',
    'index:cryptostorm:text:organization' => 'Укорененный в Исландии, финансово в Квебек / Территории первых наций, разработан, чтобы быть децентрализованным, гибким, хитрый подход к реальной сетевой безопасности.',
    'index:cryptostorm:text:members' => 'Безопасность участников в первую очередь; все остальное - просто предыстория.',
    'index:cryptostorm:text:nologs' => [
        'text' => 'Нет логов %s. Никогда не предаёт участников сети.',
        'replacers' => ['seppuku'],
    ],
    'index:cryptostorm:text:tokenbasedauth' => [
        'text' => '%s - нет связи между участником и аутентификацией.',
        'replacers' => ['tokenbased'],
    ],
    'index:cryptostorm:link:tokenbasedauth' => 'Работает на основе токена',
    'index:cryptostorm:headline:genuine' => 'Подлинный',
    'index:cryptostorm:text:genuine' => 'Никаких гипервизоров или маркетинговых уловок. Open-Source, опубликованный, Peer-Review-основы. Выделенные сервера, FDE из метала. К чёрту условия использования и политику конфиденциальности.',
    'index:cryptostorm:headline:strongcrypto' => 'Сильная криптография',
    'index:cryptostorm:text:strongcrypto' => 'От выбора алгоритма до процедур OpSec, Cryptostorm годами итеративно укрепляли защиту от атак. Нет недочётов, сломанных протоколов или косяков администрирования.',
    'index:cryptostorm:headline:battletested' => 'Испытан в бою',
    'index:cryptostorm:text:battletested' => 'Основная команда Cryptostorm способствовала запуску VPN-отрасли. Теперь они диктуют моду. Дружелюбный к активистам? По любому: они напрямую поддерживают инфо-диссидентов... на протяжении десятилетий.',

    'index:reseller:text:indepedentcsreseller' => 'независимый реселлер токенов Cryptostorm',
    'index:reseller:text:bulkpurchase' => 'Мы покупаем токены в больших количествах у Cryptostorm и предлагаем вам дополнительную анонимность.',
    'index:reseller:text:noassociation' => [
        'text' => '%s никак не связан с Cryptostorm, просто пользователь сервиса VPN',
        'replacers' => ['name'],
    ],
    'index:reseller:text:noknowledge' => [
        'text' => 'Покупка токенов через %s означает, что cryptostorm ничего не узнает о вас - нет связи между токеном &amp; деталями  покупки.',
        'replacers' => ['name'],
    ],
    'index:reseller:text:paymentprovider' => [
        'text' => 'Несколько криптовалют доступны для оплаты, в качестве платёжного шлюза мы используем %s.',
        'replacers' => ['link'],
    ],
    'index:reseller:text:noemailneeded' => 'Для оплаты не требуется электронная почта или мобильный телефон. Просто добавьте страницу в закладки после оплаты.',
    'index:reseller:text:tor' => [
        'text' => 'Для еще большей анонимности вы можете использоваь сеть TOR: %s.',
        'replacers' => ['domain'],
    ],
    'index:reseller:text:nojscookies' => 'Страница работает без JavaScript, сессий &amp; печеняк!',
    'index:reseller:text:transactions' => 'Детали транзакции удаляются вскоре после ее завершения.',
    'index:reseller:text:qrcode' => 'Помимо токенов, вы получите QR-код на странице оплаты, который был хэширован для легкого мобильного сканирования.',
    'index:reseller:headline:step1' => 'Шаг 1 - Покупка токена',
    'index:reseller:text:available' => 'Доступные токены:',
    'index:reseller:text:instock' => 'есть в наличии',
    'index:reseller:text:outofstock' => [
        'text' => 'нет в наличии %s попробуйте ещё раз позже',
        'replacers' => ['break'],
    ],
    'index:reseller:text:showhidecurrencies' => [
        'text' => 'Поддерживаемые криптовалюты %s %s',
        'replacers' => ['show', 'hide'],
    ],
    'index:reseller:text:show' => 'показать',
    'index:reseller:text:hide' => 'скрыть',
    'index:reseller:headline:step2' => 'Шаг 2 - Хэшированние токена',
    'index:reseller:text:receiveandverify' => [
        'text' => 'Получаете токент и %s его.',
        'replacers' => ['link'],
    ],
    'index:reseller:text:verify' => 'проверить',
    'index:reseller:text:calculatehash' => [
        'text' => 'Введите токен %s, чтобы создать пользователя.',
        'replacers' => ['link'],
    ],
    'index:reseller:text:calculator' => 'sha512 калькулятор',
    'index:reseller:text:nopassword' => 'Нет никакого пароля, вы можете ввести все, что вы хотите.',
    'index:reseller:headline:step3' => 'Шаг 3 - Соеденение с Cryptostorm',
    'index:reseller:text:trywidget' => [
        'text' => 'Попробуйте простой в использовании %s.',
        'replacers' => ['link'],
    ],
    'index:reseller:text:widget' => 'Windows Widget',
    'index:reseller:text:otheroses' => [
        'text' => '%s для Linux, Windows, Android, iOS и Mac.',
        'replacers' => ['link'],
    ],
    'index:reseller:text:connectionguides' => 'Подробная инструкция подключения',
    'index:reseller:text:openvpn' => [
        'text' => 'Вы можете использовать OpenVPN %s.',
        'replacers' => ['link'],
    ],
    'index:reseller:text:configurationfiles' => 'Конфигурационные файлы',

    'index:contact:headline' => [
        'text' => 'Вопросы или предложения? Будем рады %s твоему письму!',
        'replacers' => ['heart'],
    ],
    'index:contact:text:showhidepublickey' => [
        'text' => 'Публичный PGP/GPG ключ %s %s',
        'replacers' => ['show', 'hide'],
    ],
    'index:contact:text:show' => 'показать',
    'index:contact:text:hide' => 'скрыть',
    'index:contact:text:encrypted' => 'Форма шифруется с помощью OpenPGP.js, JavaScript реализация стандарта OpenPGP, так что ваше сообщение может быть расшифровано только нами.',
    'index:contact:text:unencrypted' => 'Обратите внимание что OpenPGP.js не работает, если вы включили JavaScript. Вы можете вручную зашифровать свое сообщение перед отправкой, используя наш публичный ключ.',
    'index:contact:form:email' => 'Ваш E-Mail',
    'index:contact:form:emailplaceholder' => 'your@email.com',
    'index:contact:form:subject' => 'Тема',
    'index:contact:form:subjectplaceholder' => 'Как дела?',
    'index:contact:form:message' => 'Твоё сообщение',
    'index:contact:form:messageplaceholder' => 'Так и есть...',
    'index:contact:form:submit' => 'Отправить сообщение',
    'index:contact:form:success' => 'Спасибо за ваше сообщение. Мы ответим вам как можно скорее.',
    'index:contact:form:fail' => 'Произошла ошибка при отправки сообщения. Попробуйте позже ещё раз.',

    'order:create:headline:purchasetoken' => 'Купить токен',
    'order:create:select:choosecurrency' => 'Выбрать криптовалюту для оплаты',
    'order:create:button:order' => [
        'text' => '%s (%s) купить',
        'replacers' => ['name', 'price'],
    ],
    'order:create:text:error' => 'Нам жаль, при отправке заказа произошла ошибка. Попробуйте позже ещё раз.',
    'order:create:text:currencyunavailable' => 'Нам жаль, оплата с выбранной вами криптовалютой временно недоступна. Попробуйте позже.',
    'order:create:text:pleasewait' => 'Пожалуйста подождите, после нажатия на кнопку заказа, пока генерируется адрес платежа (может занять несколько секунд).',
    'order:create:text:redirect' => 'Вы будете автоматически перенаправлены на страницу оплаты. На оплату у вас есть 30 минут.',
    'order:create:text:tokensoutofstock' => [
        'text' => 'Нам жаль, все токенты этого типа (% s) зарезервированны или их нет в наличии. Попробуйте позже.',
        'replacers' => ['name'],
    ],

    'order:cancelled:text:description' => 'Срок оплаты истёк, заказ был отменён. Если вы оплатили чуть позже, то есть шанс что транзакция прошла и статус заказа ещё измениться.',

    'order:clear:headline' => 'Удаление.',
    'order:clear:description' => 'Записи вашего заказа были помечены для удаления',

    'order:confirming:headline' => [
        'text' => '%s - Подтверждение вашей транзакции',
        'replacers' => ['name'],
    ],
    'order:confirming:text:description' => 'Ваш криптовалютный перевод был найден, но требует дальнейших подтверждений, для обеспечения транзакции.',

    'order:mispaid:text:description' => 'Вы не отправили точную сумму криптовалюты, поэтому система отклонила ваш платеж.',

    'order:unpaid:text:description' => 'Вы можете отканировать данный QR-код с помощью Mobile Wallet App, чтобы совершить оплату или переведите сумма %s на этот адрес',
    'order:unpaid:text:timeout' => 'У вас осталось %s минут, чтобы оплатить заказ.',
    'order:unpaid:button:openinwallet' => 'Открыть в кошельке',
    'order:unpaid:button:copyaddress' => 'Скопировать адрес',

    'order:paid:text:yourtoken' => 'Ваш токен',
    'order:paid:button:copytoken' => 'Скопировать токен',
    'order:paid:text:qrcode' => 'Вы можете отканировать QR-код, чтобы получать на свой смартфон захешированные имя токен пользователя.',
    'order:paid:text:info' => 'Токен это всё, что вам нужно, чтобы подключиться к Cryptostorm. Нет "Профиля" и дополнительной информации. Не теряйте свой токен, потому что мы не можем заменить его - мы навсегда удаляем записи проданных токенов. И не беспокойся об том, что ваш токен нужно сразу использовать - срок начнётся с первого подключение к сети Cryptostorm.',
    'order:paid:text:remotetimeout' => 'Данная запись будет автоматически отмечена для удаление через %s минут. Но мы рекомендуем удалить её раньше!',
    'order:paid:button:remove' => 'Удалить эту запись сейчас',

    'order:text:support' => [
        'text' => 'Если вы думайте что произошла ошибка, свяжитесь с нами указав номер заказа # %s, чтобы мы могли решить эту проблему.',
        'replacers' => ['orderId'],
    ],
    'order:text:bookmark' => [
        'text' => 'Добавьте страницу в закладки, так как мы не отправляем никакие письма. Если будут проблемы, укажите Пожалуйста номер заказа # %s, чтобы могли проверить ваш заказ.',
        'replacers' => ['orderId']
    ],
    'order:text:pleaserefresh' => 'У вас не включен Javascript, обновляйте эту странциу регулярно, чтобы увидить статус.',
    'order:html:backtohome' => 'На главную',
];
