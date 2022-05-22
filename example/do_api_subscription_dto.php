<?php

use BePaidAcquiring\BePaidClient;

require __DIR__ . '/../vendor/autoload.php';

/** @var BePaidClient $BePaidClient */
$BePaidClient = require '_client_credentials.php';
$apiClient = $BePaidClient
    ->enableTestMode() // for debug only
;

// execute
$request = new \BePaidAcquiring\Request\SubscriptionRequest(
    'http://merchant.com/subscription_notification',
    'Basic plan',
    10,
    20,
);
/** @var \BePaidAcquiring\Response\SubscriptionResponse $responseAsObj */
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
