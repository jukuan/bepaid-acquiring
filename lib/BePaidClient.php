<?php

declare(strict_types=1);

namespace BePaidAcquiring;

use BePaidAcquiring\HttpClient\CurlClient;
use BePaidAcquiring\HttpClient\HttpRequestInterface;
use BePaidAcquiring\Response\BaseResponse;
use BePaidAcquiring\Response\SubscriptionResponse;

class BePaidClient
{
    private const LIB_VERSION = '0.0.1';
    private const HTTP_HEADERS = [
        'Content-Type: application/json',
        'CMS: BePaidAcquiring',
        //'Module-Version: ' . self::LIB_VERSION,
    ];

    private const ENDPOINT_API_BASE = 'https://api.begateway.com';

    private const ENDPOINT_PROD_GATEWAY = 'https://gateway.bepaid.by';
    private const ENDPOINT_PROD_CHECKOUT = 'https://checkout.bepaid.by';

    private const ENDPOINT_TEST_GATEWAY = 'https://demo-gateway.begateway.com';
    private const ENDPOINT_TEST_CHECKOUT = 'https://checkout.begateway.com';

    public const BYN_CURRENCY = 933;
    public const EUR_CURRENCY = 978;
    public const USD_CURRENCY = 840;

    public const CURRENCIES = [
        'BYN' => self::BYN_CURRENCY,
        'EUR' => self::EUR_CURRENCY,
        'USD' => self::USD_CURRENCY,
    ];

    /**
     * Language codes in "ISO 639-1" format.
     * https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
     */
    private const LANGUAGE_BE = 'be';
    private const LANGUAGE_EN = 'en';
    private const LANGUAGE_RU = 'ru';
    private const LANGUAGES = [
        self::LANGUAGE_BE,
        self::LANGUAGE_EN,
        self::LANGUAGE_RU,
    ];
    private const DEFAULT_LANGUAGE = self::LANGUAGE_BE;

    private ?bool $isTestMode = null;
    //private string $language = self::DEFAULT_LANGUAGE;
    private int $currency = self::BYN_CURRENCY;
    private HttpRequestInterface $client;
    private string $errorMessage = '';

    private int $shopId;
    private string $shopKey;
    private string $gatewayBase;
    private string $checkoutBase;

    public function __construct(
        int $shopId,
        string $shopKey,
        array $parameters = []
    ) {
        $this->shopId = $shopId;
        $this->shopKey = $shopKey;

        if (!$this->shopId || !$this->shopKey) {
            throw new \InvalidArgumentException('Shop ID and Shop Key are required');
        }

        if (null === $this->isTestMode) {
            $testMode = ($parameters['test'] ?? false);
            $this->isTestMode = true === $testMode || 1 === $testMode || 'true' === $testMode;
        }

        $this->gatewayBase = $parameters['gatewayBase'] ?? ($this->isTestMode ? self::ENDPOINT_TEST_GATEWAY : self::ENDPOINT_PROD_GATEWAY);
        $this->checkoutBase = $parameters['checkoutBase'] ?? ($this->isTestMode ? self::ENDPOINT_TEST_CHECKOUT : self::ENDPOINT_PROD_CHECKOUT);

        if ('' === $this->gatewayBase || '' === $this->checkoutBase) {
            throw new \InvalidArgumentException('Gateway and checkout base URLs must be specified');
        }

        $this->client = (new CurlClient())->setHttpHeaders(self::HTTP_HEADERS);
    }

    public function setLanguage(string $lang): BePaidClient
    {
        if (in_array($lang, self::LANGUAGES, true)) {
            //$this->language = $lang;
        }

        return $this;
    }

    public function setTimeout(int $timeout): BePaidClient
    {
        $this->client->setTimeout($timeout);

        return $this;
    }

    private function reset(): void
    {
        $this->errorMessage = '';
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    private function prepareMethod(string $method): string
    {
        return trim($method, '/');
    }

    private function prepareApiUrl(string $method): string
    {
        $url = self::ENDPOINT_API_BASE;

        return $url . '/' . $this->prepareMethod($method);
    }

    private function prepareGatewayUrl(string $method): string
    {
        $url = $this->isTestMode ? self::ENDPOINT_TEST_GATEWAY : self::ENDPOINT_PROD_GATEWAY;

        return $url . '/' . $this->prepareMethod($method);
    }

    private function prepareCheckoutUrl(string $method): string
    {
        $url = $this->isTestMode ? self::ENDPOINT_TEST_CHECKOUT : self::ENDPOINT_PROD_CHECKOUT;

        return $url . '/' . $this->prepareMethod($method);
    }

    /**
     * @param string $method
     * @param array|string $postData
     * @return bool
     */
    public function doMethod(string $method, $postData = '', string $mode = 'api'): bool
    {
        $this->reset();

        if (is_array($postData)) {
            $postData = count($postData) > 0 ? json_encode($postData) : '';
        }

        if ('api' === $mode) {
            $url = $this->prepareApiUrl($method);
        } elseif ('gateway' === $mode) {
            $url = $this->prepareGatewayUrl($method);
        } elseif ('checkout' === $mode) {
            $url = $this->prepareCheckoutUrl($method);
        } else {
            $this->errorMessage = 'Unknown method\'s mode';

            return false;
        }

        $this->client->execute($url, $postData, [
            CURLOPT_USERPWD => $this->getAuthorisationPwd(),
        ]);

        if ($this->client->hasError()) {
            $this->errorMessage = $this->client->getErrorDetails();

            return false;
        }

        return true;
    }

    public function createSubscription(array $data): SubscriptionResponse
    {
        if (!$this->doMethod('subscriptions', $data)) {
            SubscriptionResponse::initialiseFailed($this->getErrorMessage());
        }

        return new SubscriptionResponse($this->getResponseFields());
    }

    public function getResponseFields(): array
    {
        return (array) $this->client->getDecodedResponse();
    }

    public function getResponse(): BaseResponse
    {
        return (new BaseResponse($this->getResponseFields()));
    }

    public function enableTestMode(): BePaidClient
    {
        $this->isTestMode = true;

        return $this;
    }

    /*public function getLastQuery(): string
    {
        return $this->client->getLastQuery();
    }*/

    public function getHttpResponseCode(): int
    {
        return $this->client->getHttpResponseCode();
    }

    private function getAuthorisationPwd(): string
    {
        return $this->shopId . ':' . $this->shopKey;
    }
}
