<?php

declare(strict_types=1);

return [
    'layout:link:contact' => 'Kontakt',
    'layout:link:cryptostorm' => 'Cryptostorm Links',
    'layout:text:contentstolenwithpride' => 'Inhalte stolz geborgt von',
    'layout:text:and' => 'und',

    'token:name:oneweek' => '1 Woche',
    'token:name:onemonth' => '1 Monat',
    'token:name:threemonths' => '3 Monate',
    'token:name:sixmonths' => '6 Monate',
    'token:name:oneyear' => '1 Jahr',
    'token:name:twoyears' => '2 Jahre',
    'token:name:lifetime' => 'Lebenszeit',

    'error:text:oops' => 'Uups. Das ist jetzt peinlich.',
    'error:text:reason' => [
        'text' => 'Es ist ein Problem (%s) aufgetreten.',
        'replacers' => ['reason'],
    ],
    'error:text:404' => [
        'text' => 'Sie suchen nach etwas, das nicht existiert oder sich bewegt haben könnte. Schauen Sie sich einen der Links auf dieser Seite an oder gehen Sie zurück zu $s.',
        'replacers' => ['home'],
    ],
    'error:link:home' => 'Startseite',

    'index:cryptostorm:text:catchphrase' => 'Cryptostorm. Sicher, anonym, erschwinglich, VPN-Zugang',
    'index:cryptostorm:text:connections' => [
        'text' => '%s über %s oder %s',
        'replacers' => ['easyconnect', 'windowswidget', 'openvpn']
    ],
    'index:cryptostorm:link:easytoconnect' => 'Einfache Verbindung',
    'index:cryptostorm:link:windowswidget' => 'Windows Widget',
    'index:cryptostorm:link:openvpn' => 'OpenVPN',
    'index:cryptostorm:text:adjectives' => 'VPN-Dienst, strukturell anonymer, Token-basierter Open-Source-Dienst mit 50 Megabit / Sek.',
    'index:cryptostorm:text:organization' => 'Die in Island verwurzelten Finanzdaten über die Territorien von Québec / First Nations wurden so konzipiert, dass sie eine dezentrale, flexible und schlaue Herangehensweise an die reale Netzwerksicherheit verkörpern.',
    'index:cryptostorm:text:members' => 'Sicherheit der Mitglieder zuerst; alles andere ist nur Hintergrundgeschichte.',
    'index:cryptostorm:text:nologs' => [
        'text' => 'Keine Protokolle %s. Verraten Sie niemals Netzwerkmitglieder.',
        'replacers' => ['seppuku'],
    ],
    'index:cryptostorm:text:tokenbasedauth' => [
        'text' => '%s - echte Entkopplung von Kunden und Authentifizierung.',
        'replacers' => ['tokenbased'],
    ],
    'index:cryptostorm:link:tokenbasedauth' => 'Token-basiert',
    'index:cryptostorm:headline:genuine' => 'Original',
    'index:cryptostorm:text:genuine' => 'Keine Hypeware oder Marketing-Gimmicks. Open-Source, veröffentlicht, Peer-Review-Grundlagen. Dedizierte Server, FDE aus dem Metall. No-Bullshit Nutzungsbedingungen und Datenschutzerklärung.',
    'index:cryptostorm:headline:strongcrypto' => 'Starkes Krypto',
    'index:cryptostorm:text:strongcrypto' => 'Von der Wahl des Algorithmus bis zum OpSec-Verfahren haben sie Angriffsoberflächen über Jahre iterativ gehärtet. Keine Unterdrückung, fehlerhafte Protokolle oder Slapdash-Administration.',
    'index:cryptostorm:headline:battletested' => 'Gefecht getestet',
    'index:cryptostorm:text:battletested' => 'Das Kernteam von Cryptostorm hat dazu beigetragen, die VPN-Branche auf den Weg zu bringen. Seither haben sie den möglichen Schritt neu definiert. Aktivist freundlich? In der Tat: sie haben direkt Dissidenten... unterstützt; für Jahrzehnte.',

    'index:reseller:text:indepedentcsreseller' => 'unabhängiger Cryptostorm-Token-Reseller',
    'index:reseller:text:bulkpurchase' => 'Wir kaufen Token in großen Mengen von Cryptostorm und geben Ihnen die zusätzliche Anonymität weiter.',
    'index:reseller:text:noassociation' => [
        'text' => '%s steht in keiner Weise mit cryptostorm in Verbindung, einfach ein anderer Benutzer des vpn-Dienstes.',
        'replacers' => ['name'],
    ],
    'index:reseller:text:noknowledge' => [
        'text' => 'Der Kauf von Token über %s bedeutet, dass cryptostorm nichts über Sie wissen kann - keine Verbindung zwischen einem Token &amp; Einzelheiten zum Kauf.',
        'replacers' => ['name'],
    ],
    'index:reseller:text:paymentprovider' => [
        'text' => 'Mehrere Kryptocoins zur Zahlung verfügbar, als Zahlungsanbieter verwenden wir %s.',
        'replacers' => ['link'],
    ],
    'index:reseller:text:noemailneeded' => 'Für die Zahlung wird keine E-Mail benötigt. Lesezeichen setzen Sie einfach auf die Zahlungsseite.',
    'index:reseller:text:tor' => [
        'text' => 'Für noch mehr Anonymität können Sie uns auch auf TOR erreichen: %s.',
        'replacers' => ['domain'],
    ],
    'index:reseller:text:nojscookies' => 'Seite funktioniert mit deaktiviertem JavaScript, keine Sitzungen & amp; keine Kekse!',
    'index:reseller:text:transactions' => 'Transaktionsdetails werden kurz nach Abschluss der Transaktion gelöscht.',
    'index:reseller:text:qrcode' => 'Zusätzlich zu Token erhalten Sie einen QR-Code auf der bezahlten Seite, der für einfaches mobiles Scannen vorgehashed wurde.',
    'index:reseller:headline:step1' => 'Schritt 1 - Kauf-Token',
    'index:reseller:text:available' => 'Verfügbare Token:',
    'index:reseller:text:instock' => 'auf Lager',
    'index:reseller:text:outofstock' => [
        'text' => 'zur Zeit nicht vorrätig %s bitte später zurück überprüfen',
        'replacers' => ['break'],
    ],
    'index:reseller:text:showhidecurrencies' => [
        'text' => 'Unterstützte Kryptowährungen %s %s',
        'replacers' => ['show', 'hide'],
    ],
    'index:reseller:text:show' => 'anzeigen',
    'index:reseller:text:hide' => 'verstecken',
    'index:reseller:headline:step2' => 'Schritt 2 - Hash-token',
    'index:reseller:text:receiveandverify' => [
        'text' => 'Erhalten Sie ein Netzwerk-Token und %s Sie es.',
        'replacers' => ['link'],
    ],
    'index:reseller:text:verify' => 'verifizieren',
    'index:reseller:text:calculatehash' => [
        'text' => 'Fügen Sie Ihr Netzwerk-Token in das %s ein, um Ihren Benutzernamen zu generieren.',
        'replacers' => ['link'],
    ],
    'index:reseller:text:calculator' => 'sha512 calculator',
    'index:reseller:text:nopassword' => 'Es gibt kein Passwort, Sie können eingeben, was Sie wollen.',
    'index:reseller:headline:step3' => 'Schritt 3 - Verbinden mit Cryptostorm',
    'index:reseller:text:trywidget' => [
        'text' => 'Probieren Sie die einfach zu verwendenden %s aus.',
        'replacers' => ['link'],
    ],
    'index:reseller:text:widget' => 'Windows Widget',
    'index:reseller:text:otheroses' => [
        'text' => '%s für Linux, Windows, Android, iOS und Mac.',
        'replacers' => ['link'],
    ],
    'index:reseller:text:connectionguides' => 'Detaillierte Verbindungshilfen',
    'index:reseller:text:openvpn' => [
        'text' => 'Sie können immer OpenVPN %s verwenden.',
        'replacers' => ['link'],
    ],
    'index:reseller:text:configurationfiles' => 'Konfigurationsdateien',

    'index:contact:headline' => [
        'text' => 'Fragen oder Anregungen? Wir %s uns von dir zu hören!',
        'replacers' => ['heart'],
    ],
    'index:contact:text:showhidepublickey' => [
        'text' => 'Public PGP/GPG key %s %s',
        'replacers' => ['show', 'hide'],
    ],
    'index:contact:text:show' => 'anzeigen',
    'index:contact:text:hide' => 'verstecken',
    'index:contact:text:encrypted' => 'Dieses Formular ist mit OpenPGP.js gesichert, einer JavaScript-Implementierung des OpenPGP-Standards, sodass Ihre Nachricht nur von uns entschlüsselt werden kann.',
    'index:contact:text:unencrypted' => 'Please note that OpenPGP.js doesn\'t work when JavaScript is disabled. You can manually encrypt your message before sending by using our public key.',
    'index:contact:form:email' => 'Deine E-Mail',
    'index:contact:form:emailplaceholder' => 'your@email.com',
    'index:contact:form:subject' => 'Gegenstand',
    'index:contact:form:subjectplaceholder' => 'Was geht?',
    'index:contact:form:message' => 'Deine Nachricht',
    'index:contact:form:messageplaceholder' => 'So war es...',
    'index:contact:form:submit' => 'Nachricht abschicken',
    'index:contact:form:success' => 'Vielen Dank für Ihre Nachricht. Wir werden Ihnen so schnell wie möglich antworten.',
    'index:contact:form:fail' => 'Beim Senden Ihrer Nachricht ist ein Fehler aufgetreten. Bitte versuche es später erneut.',

    'order:create:headline:purchasetoken' => 'Token kaufen',
    'order:create:select:choosecurrency' => 'Wählen Sie Checkout-Kryptowährung aus',
    'order:create:button:order' => [
        'text' => '%s (%s) kaufen',
        'replacers' => ['name', 'price'],
    ],
    'order:create:text:error' => 'Es tut uns leid, es gab einen Fehler beim Absenden Ihrer Bestellung. Bitte versuche es später erneut.',
    'order:create:text:currencyunavailable' => 'Es tut uns leid, die von Ihnen ausgewählte Kryptowährung ist vorübergehend nicht verfügbar. Bitte wählen Sie ein anderes.',
    'order:create:text:pleasewait' => 'Bitte warten Sie, nachdem Sie auf die Bestellschaltfläche geklickt haben, während die Zahlungsadresse generiert wird (dieser Vorgang kann mehrere Sekunden dauern).',
    'order:create:text:redirect' => 'Sie werden automatisch auf die Bezahlseite umgeleitet, dann haben Sie 30 Minuten Zeit, um die Cryptocoin-Transaktion durchzuführen.',
    'order:create:text:tokensoutofstock' => [
        'text' => 'Es tut uns leid, alle Tokens dieses Typs (% s) sind derzeit reserviert oder nicht auf Lager. Bitte versuche es später erneut.',
        'replacers' => ['name'],
    ],

    'order:cancelled:text:description' => 'Sie haben die Zahlung nicht rechtzeitig gesendet, sodass Ihre Bestellung storniert wurde. Wenn Sie es nur ein wenig zu spät gesendet haben, besteht immer noch die Möglichkeit, dass die Transaktion ausgeführt wird und Ihre Bestellung in den bezahlten Status versetzt wird.',

    'order:clear:headline' => 'Entfernt.',
    'order:clear:description' => 'Die Datensätze Ihrer Bestellung wurden zur Entfernung markiert.',

    'order:confirming:headline' => [
        'text' => '%s - Bestätigung Ihrer Transaktion',
        'replacers' => ['name'],
    ],
    'order:confirming:text:description' => 'Ihr Cryptocoin-Transfer wurde gefunden, benötigt jedoch weitere Bestätigungen, um die Transaktion zu sichern.',

    'order:mispaid:text:description' => 'Sie haben nicht die genaue Menge an Kryptowährung gesendet, so dass das System Ihre Zahlung abgelehnt hat.',

    'order:unpaid:text:description' => 'Sie können diesen QR-Code mit Ihrer Mobile Wallet App scannen, um eine Zahlung zu tätigen, oder senden Sie genau %s an diese Adresse',
    'order:unpaid:text:timeout' => 'Sie haben %s Minuten, um diese Zahlung abzuschließen.',
    'order:unpaid:button:openinwallet' => 'In Wallet öffnen',
    'order:unpaid:button:copyaddress' => 'Adresse kopieren',

    'order:paid:text:yourtoken' => 'Dein Token ist',
    'order:paid:button:copytoken' => 'Token kopieren',
    'order:paid:text:qrcode' => 'Sie können den QR-Code scannen, um den bereits verschlüsselten Token-Benutzernamen auf Ihrem Smartphone zu erhalten.',
    'order:paid:text:info' => 'Ihr Token ist alles, was Sie benötigen, um sich mit Cryptostorm zu verbinden. Es gibt kein "Konto" und keine zusätzlichen Informationen. Verlieren Sie Ihr Token nicht, da wir es nicht ersetzen können - wir löschen permanent Aufzeichnungen von verkauften Tokens. Und sorgen Sie sich nicht darum, Ihr Token sofort zu verwenden - es beginnt nicht gegen den Ablauf zu "erodieren", bis Sie sich das erste Mal beim Cryptostorm anmelden.',
    'order:paid:text:remotetimeout' => 'Dieser Datensatz wird automatisch in %s Minuten zur Entfernung markiert. Wir empfehlen Ihnen, es früher zu entfernen.',
    'order:paid:button:remove' => 'Entferne diesen Datensatz jetzt',

    'order:text:support' => [
        'text' => 'Wenn Sie der Meinung sind, dass ein Fehler aufgetreten ist, kontaktieren Sie uns bitte mit Ihrer Bestellnummer # %s, damit wir das Problem lösen können.',
        'replacers' => ['orderId'],
    ],
    'order:text:bookmark' => [
        'text' => 'Setzen Sie ein Lesezeichen für diese Seite, da wir nicht mit E-Mail-Adressen arbeiten. Bei Problemen geben Sie bitte Ihre Bestellung # %s, damit wir Ihre Bestellung überprüfen können.',
        'replacers' => ['orderId']
    ],
    'order:text:pleaserefresh' => 'Da Javascript nicht aktiviert ist, aktualisieren Sie die Seite regelmäßig, um Statusänderungen zu sehen.',
    'order:html:backtohome' => 'zurück zur Startseite',
];
