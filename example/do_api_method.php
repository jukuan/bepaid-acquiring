<?php

use BePaidAcquiring\BePaidClient;

require __DIR__ . '/../vendor/autoload.php';

/** @var BePaidClient $BePaidClient */
$BePaidClient = require '_client_credentials.php';
$apiClient = $BePaidClient
    ->enableTestMode() // for debug only
;

// execute custom API method
$params = '{}';
$result = $apiClient->doMethod('', $params);

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
