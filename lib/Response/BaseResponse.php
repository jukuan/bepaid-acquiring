<?php

declare(strict_types=1);

namespace BePaidAcquiring\Response;

use Exception;

class BaseResponse implements ResponseInterface
{
    protected array $response = [];

    protected ?Exception $error = null;

    /**
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        $this->response = $fields;
        $this->checkErrorFields($fields);
    }

    public static function initialiseFailed(string $errorMsg, int $errorCode = 0): BaseResponse
    {
        return (new static([]))
            ->setErrorFields($errorMsg, $errorCode);
    }

    protected function setErrorException(Exception $exception): void
    {
        $this->error = $exception;
    }

    protected function setErrorMessageCode(string $msg, int $code = 0): BaseResponse
    {
        if ('' !== $msg || $code > 0) {
            $this->setErrorException(new Exception($msg, $code));
        }

        return $this;
    }

    protected function setErrorFields(string $errorMessage, $code = 0): BaseResponse
    {
        $this->setErrorMessageCode($errorMessage, $code);

        return $this;
    }

    public function getErrorMessage(): ?string
    {
        if (null === $this->error) {
            return null;
        }

        return $this->error->getMessage();
    }

    public function getErrorCode(): int
    {
        if (null === $this->error) {
            return 0;
        }

        return (int) $this->error->getCode();
    }

    public function isValid(): bool
    {
        if (null === $this->error) {
            return true;
        }

        return false;
    }

    public function getField(string $fieldName)
    {
        if (isset($this->response[$fieldName])) {
            return $this->response[$fieldName];
        }

        return null;
    }

    public function getResponse(): array
    {
        return $this->response;
    }

    public function __sleep()
    {
        return ['response'];
    }

    protected function checkErrorFields(array $fields): bool
    {
        if (isset($fields['errors']) && is_array($fields['errors'])) {
            $errorMsg = $fields['message'] ?? '';

            if ('' === $errorMsg) {
                $errors = reset($fields['errors']);
                $errorMessages = is_array($errors) ? reset($errors) : $errors;
                $errorMsg = is_array($errorMessages) ? reset($errorMessages) : '';

                if (!is_string($errorMsg)) {
                    $errorMsg = '';
                }
            }

            $this->setErrorFields($errorMsg);
        }

        return isset($fields['errors']) || isset($fields['error']);
    }
}
