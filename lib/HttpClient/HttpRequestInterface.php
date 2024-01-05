<?php

declare(strict_types=1);

namespace BePaidAcquiring\HttpClient;

interface HttpRequestInterface
{
    public function setOption(int $name, $value): HttpRequestInterface;

    public function setHttpHeaders(array $headers): HttpRequestInterface;

    public function addCurlOpt(int $key, string $value): HttpRequestInterface;

    public function setRequestType(string $requestType): HttpRequestInterface;

    public function setPostBody($postData): HttpRequestInterface;

    public function getHttpResponseCode(): int;

    public function setTimeout(int $timeout): HttpRequestInterface;

    public function getHttpResponse(): ?string;

    public function execute(string $url, array $optFields = []): HttpRequestInterface;

    public function getDecodedResponse();

    public function hasError(): bool;

    public function getErrorDetails(): string;

    public function getInfo(?int $name);

    public function initialise(): void;

    public function close(): void;
}
