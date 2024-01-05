<?php

declare(strict_types=1);

namespace BePaidAcquiring;

use BePaidAcquiring\HttpClient\CurlClient;
use BePaidAcquiring\HttpClient\HttpRequestInterface;
use BePaidAcquiring\Response\BaseResponse;
use BePaidAcquiring\Response\SubscriptionResponse;
use InvalidArgumentException;

class BePaidClient
{
    private const HTTP_HEADERS = [
        'Accept: application/json',
        'CMS: BePaidAcquiring',
    ];

    private const ENDPOINT_PROD_API_BASE = 'https://api.bepaid.by';
    private const ENDPOINT_TEST_API_BASE = 'https://api.begateway.com';

    private const ENDPOINT_PROD_GATEWAY = 'https://gateway.bepaid.by';
    private const ENDPOINT_PROD_CHECKOUT = 'https://checkout.bepaid.by';

    private const ENDPOINT_TEST_GATEWAY = 'https://demo-gateway.begateway.com';
    private const ENDPOINT_TEST_CHECKOUT = 'https://checkout.begateway.com';

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
    private string $language = self::DEFAULT_LANGUAGE;
    private HttpRequestInterface $client;
    private string $errorMessage = '';

    private int $shopId;
    private string $shopKey;
    private string $gatewayBase;
    private string $checkoutBase;
    private string $lastQuery = '';

    public function __construct(
        int $shopId,
        string $shopKey,
        array $parameters = []
    ) {
        $this->shopId = $shopId;
        $this->shopKey = $shopKey;

        if (!$this->shopId || !$this->shopKey) {
            throw new InvalidArgumentException('Shop ID and Shop Key are required');
        }

        if (null === $this->isTestMode) {
            $testMode = ($parameters['test'] ?? false);
            $this->isTestMode = true === $testMode || 1 === $testMode || 'true' === $testMode;
        }

        $this->gatewayBase = $parameters['gatewayBase'] ?? ($this->isTestMode ? self::ENDPOINT_TEST_GATEWAY : self::ENDPOINT_PROD_GATEWAY);
        $this->checkoutBase = $parameters['checkoutBase'] ?? ($this->isTestMode ? self::ENDPOINT_TEST_CHECKOUT : self::ENDPOINT_PROD_CHECKOUT);

        if ('' === $this->gatewayBase || '' === $this->checkoutBase) {
            throw new InvalidArgumentException('Gateway and checkout base URLs must be specified');
        }

        $this->client = (new CurlClient())->setHttpHeaders(self::HTTP_HEADERS);
    }

    public function setLanguage(string $lang): BePaidClient
    {
        if (in_array($lang, self::LANGUAGES, true)) {
            $this->language = $lang;
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
        $this->lastQuery = '';
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
        $url = $this->isTestMode ? self::ENDPOINT_TEST_API_BASE : self::ENDPOINT_PROD_API_BASE;

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

    private function prepareRequestUrl(string $method, string $mode = 'api'): string
    {
        if ('api' === $mode) {
            $url = $this->prepareApiUrl($method);
        } elseif ('gateway' === $mode) {
            $url = $this->prepareGatewayUrl($method);
        } elseif ('checkout' === $mode) {
            $url = $this->prepareCheckoutUrl($method);
        } else {
            throw new \Exception('Unknown method\'s mode');
        }

        return $url;
    }

    /**
     * @param string $method
     * @param $postData
     * @param string $mode
     *
     * @return bool
     *
     * @throws \Exception
     * @deprecated
     *
     */
    public function doMethod(string $method, $postData = '', string $mode = 'api'): bool
    {
        $url = $this->prepareBeforeRequest($method, $mode);

        if ($postData) {
            $this->client->setPostBody($postData);
            $this->lastQuery = 'POST '.$url;
        } else {
            $this->client->setRequestType('GET');
            $this->lastQuery = 'GET '.$url;
        }

        $this->client->execute($url);

        return $this->handleAfterRequest();
    }

    /**
     * @throws \Exception
     */
    public function get(string $method, string $mode = 'api'): bool
    {
        $url = $this->prepareBeforeRequest($method, $mode);
        $this->lastQuery = 'GET '.$url;

        $this->client
            ->setRequestType('GET')
            ->execute($url);

        return $this->handleAfterRequest();
    }

    /**
     * @param string $method
     * @param string|array $postBody
     * @param string $mode
     *
     * @return bool
     * @throws \Exception
     */
    public function post(string $method, $postBody = '', string $mode = 'api'): bool
    {
        $url = $this->prepareBeforeRequest($method, $mode);
        $this->lastQuery = 'POST '.$url;

        $this->client->setPostBody($postBody)->execute($url);

        return $this->handleAfterRequest();
    }

    /**
     * @throws \Exception
     */
    public function put(string $method, string $body = '', string $mode = 'api'): bool
    {
        $url = $this->prepareBeforeRequest($method, $mode);
        $this->lastQuery = 'PUT '.$url;

        $this->client
            ->setRequestType('PUT')
            ->setPostBody($body)
            ->execute($url);

        return $this->handleAfterRequest();
    }

    /**
     * @throws \Exception
     */
    public function delete(string $method, string $mode = 'api'): bool
    {
        $url = $this->prepareBeforeRequest($method, $mode);
        $this->lastQuery = 'DELETE '.$url;

        $this->client
            ->setRequestType('DELETE')
            ->execute($url);

        return $this->handleAfterRequest();
    }

    /**
     * @throws \Exception
     */
    public function createSubscription(array $data): SubscriptionResponse
    {
        if (!$this->post('subscriptions', $data)) {
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

    public function getLastQuery(): string
    {
        return $this->lastQuery;
    }

    public function getHttpResponseCode(): int
    {
        return $this->client->getHttpResponseCode();
    }

    /**
     * @throws \Exception
     */
    protected function prepareBeforeRequest(string $apiMethod, string $apiMode): string
    {
        $this->reset();

        $this->client->addCurlOpt(CURLOPT_USERPWD, $this->getAuthorisationPwd());

        return $this->prepareRequestUrl($apiMethod, $apiMode);
    }

    protected function handleAfterRequest(): bool
    {
        if ($this->client->hasError()) {
            $this->errorMessage = $this->client->getErrorDetails();

            return false;
        }

        return true;
    }

    private function getAuthorisationPwd(): string
    {
        return $this->shopId . ':' . $this->shopKey;
    }
}
