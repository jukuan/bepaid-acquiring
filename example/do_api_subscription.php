<?php

use BePaidAcquiring\BePaidClient;

require __DIR__ . '/../vendor/autoload.php';

/** @var BePaidClient $BePaidClient */
$BePaidClient = require '_client_credentials.php';
$apiClient = $BePaidClient
    ->enableTestMode() // for debug only
;

// execute custom API method
$params = '
{
    "notification_url": "http://merchant.com/subscription_notification",
    "plan": {
    "currency": "USD",
        "plan": {
        "amount": 20,
            "interval": 20,
            "interval_unit": "day"
        },
        "shop_id": 10,
        "title": "Basic plan",
        "trial": {
        "amount": 10,
            "interval": 10,
            "interval_unit": "hour"
        }
    },
    "settings": {
    "language": "it"
    }
}
';
$result = $apiClient->doMethod('subscriptions', $params);

if ($result) {
    $responseFields = $apiClient->getResponseFields();
    $responseAsObj = $apiClient->getResponse();

    print '<pre>';
    var_dump($responseFields);
    var_dump($responseAsObj);
    print '</pre>';
} else {
    $errorMsg = $apiClient->getErrorMessage();

    print '<pre>';
    var_dump($errorMsg);
    print '</pre>';
}
