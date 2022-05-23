<?php

use BePaidAcquiring\BePaidClient;
use BePaidAcquiring\Request\SubscriptionRequest;
use BePaidAcquiring\Response\SubscriptionResponse;

require __DIR__ . '/../vendor/autoload.php';

/**
 * Documentation https://docs.bepaid.by/ru/subscriptions/subscriptions#create-subscription
 */

/** @var BePaidClient $BePaidClient */
$BePaidClient = require '_client_credentials.php';
$apiClient = $BePaidClient
    ->enableTestMode() // for debug only
;

// execute
$request = new SubscriptionRequest(
    'http://merchant.com/subscription_notification',
    'Basic plan',
    10,
    20,
);
/** @var SubscriptionResponse $responseAsObj */
$responseAsObj = $apiClient->createSubscription($request->toArray());

if ($responseAsObj->isValid()) {
    $url1 = $responseAsObj->getField('redirect_url');
    $url2 = $responseAsObj->getRedirectUrl();

    print '<pre>';
    var_dump($responseAsObj);
    var_dump($url1);
    var_dump($url2);
    print '</pre>';
} else {
    $errorMsg = $apiClient->getErrorMessage();

    print '<pre>';
    var_dump($errorMsg);
    print '</pre>';
}
