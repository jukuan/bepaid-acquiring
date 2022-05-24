<?php

use BePaidAcquiring\BePaidClient;

require __DIR__ . '/../vendor/autoload.php';

/**
 * Documentation https://docs.bepaid.by/ru/subscriptions/subscriptions#create-subscription
 */

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
        "currency": "BYN",
        "plan": {
            "amount": 20,
            "interval": 1,
            "interval_unit": "month"
        },
        "shop_id": 10,
        "title": "Basic plan"
    },
    "settings": {
        "language": "be"
    }
}
';
$result = $apiClient->doMethod('subscriptions', $params);

if ($result) {
    $responseFields = $apiClient->getResponseFields();
    $responseAsObj = $apiClient->getResponse();
    $url = $responseAsObj->getField('redirect_url');

    print '<pre>';
    var_dump($responseFields);
    var_dump($url);
    print '</pre>';
} else {
    $errorMsg = $apiClient->getErrorMessage();

    print '<pre>';
    var_dump($errorMsg);
    print '</pre>';
}
