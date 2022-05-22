<?php

declare(strict_types=1);

namespace BePaidAcquiring\HttpClient;

use Exception;

class CurlClient implements HttpRequestInterface
{
    private int $timeout = 0;

    private ?CurlException $exception = null;

    /**
     * @param null|resource
     */
    private $ch = null;

    /**
     * @var mixed
     */
    private $decodedResponse = null;

    /**
     * @var string[]
     */
    private array $httpHeaders = [];

    private int $httpCode = 0;

    private function buildCurlOptions(string $url, string $postData = '', array $options = []): array
    {
        //$options[CURLOPT_SSL_VERIFYHOST] = true;
        $options[CURLOPT_SSL_VERIFYPEER] = true;
        $options[CURLOPT_URL] = $url;
        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLOPT_ENCODING] = true;

        if ($this->timeout > 0) {
            $options[CURLOPT_CONNECTTIMEOUT] = $this->timeout;
        }

        if (strlen($postData) > 0) {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $postData;
        }

        if (count($this->httpHeaders) > 0) {
            $options[CURLOPT_HTTPHEADER] = $this->httpHeaders;
        }

        return $options;
    }

    private function decodeResponse(?string $response): void
    {
        if (null === $response || '' === $response) {
            return;
        }

        try {
            $this->decodedResponse = json_decode($response, true) ?: null;
        } catch (Exception $exception) {
            $this->exception = CurlException::create($exception);
        }
    }

    public function execute(string $url, string $postData = '', array $optFields = []): CurlClient
    {
        $this->initialise();
        $options = $this->buildCurlOptions($url, $postData, $optFields);

        if (!curl_setopt_array($this->ch, $options)) {
            $this->setError('Cannot set curl options', $this->ch);
        }

        $response = $this->getHttpResponse();
        $this->httpCode = $this->getCurlHttpCode();

        if (is_string($response)) {
            $this->decodeResponse($response);
        } else {
            $error = curl_error($this->ch) ?: 'Bad response';
            $this->setError($error, $this->ch);
        }

        $this->close();

        return $this;
    }

    /**
     * @return CurlException|null
     */
    public function getException(): ?CurlException
    {
        return $this->exception;
    }

    public function getErrorMessage(): string
    {
        if (null === $this->exception) {
            return '';
        }

        return $this->exception->getMessage();
    }

    public function getHttpResponse(): ?string
    {
        if (null === $this->ch) {
            return null;
        }

        return curl_exec($this->ch) ?: null;
    }

    private function getCurlHttpCode(): int
    {
        return $this->ch ? (int) curl_getinfo($this->ch, CURLINFO_HTTP_CODE) : 0;
    }

    public static function isSuccessHttpStatusCode(int $httpStatusCode): bool
    {
        return $httpStatusCode >= 200 && $httpStatusCode < 300;
    }

    /**
     * @return mixed
     */
    public function getDecodedResponse()
    {
        if ($this->hasError()) {
            return null;
        }

        return $this->decodedResponse;
    }

    public function setTimeout(int $timeout): CurlClient
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * @param string $msg
     *
     * @param resource $handle
     */
    private function setError(string $msg, $handle = null): void
    {
        $this->exception = CurlException::generate($msg, $handle);
    }

    public function getErrorDetails(): string
    {
        if (null === $this->exception) {
            return '';
        }

        return $this->exception->getDetails();
    }

    public function hasError(): bool
    {
        return null !== $this->exception;
    }

    /**
     * @param string[] $headers
     *
     * @return CurlClient
     */
    public function setHttpHeaders(array $headers): CurlClient
    {
        $this->httpHeaders = $headers;

        return $this;
    }

    public function setOption(int $name, $value): CurlClient
    {
        curl_setopt($this->ch, $name, $value);

        return $this;
    }

    public function getInfo(?int $name)
    {
        return curl_getinfo($this->ch, $name);
    }

    public function reset(): void
    {
        $this->httpCode = 0;
        $this->decodedResponse = null;
        $this->exception = null;
    }

    public function initialise(): void
    {
        $this->reset();

        $handle = curl_init();

        if (false === $handle) {
            $this->setError('Cannot initialise curl');
        } else {
            $this->ch = $handle;
        }
    }

    public function close(): void
    {
        if (null !== $this->ch) {
            curl_close($this->ch);
        }
    }
    public function getHttpResponseCode(): int
    {
        return $this->httpCode;
    }
}
