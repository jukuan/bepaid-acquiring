<?php

namespace BePaidAcquiring\DTO\Subscription;

use BePaidAcquiring\DTO\BaseDto;
use DateTimeImmutable;

class Transaction extends BaseDto
{
    private ?DateTimeImmutable $created_at = null;
    private ?string $message = null;
    private ?string $status = null;
    private ?string $uid = null;

    public static function createFromArray($fields): Transaction
    {
        if (!is_array($fields)) {
            return new static();
        }

        $dto = new Transaction();
        $dto->setCreatedAt(isset($fields['created_at']) ? self::toDateTime($fields['created_at']) : null);
        $dto->setMessage($fields['message'] ?? null);
        $dto->setStatus($fields['status'] ?? null);
        $dto->setUid($fields['uid'] ?? null);

        return $dto;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * @param DateTimeImmutable|null $created_at
     * @return Transaction
     */
    public function setCreatedAt(?DateTimeImmutable $created_at): Transaction
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     * @return Transaction
     */
    public function setMessage(?string $message): Transaction
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     * @return Transaction
     */
    public function setStatus(?string $status): Transaction
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUid(): ?string
    {
        return $this->uid;
    }

    /**
     * @param string|null $uid
     * @return Transaction
     */
    public function setUid(?string $uid): Transaction
    {
        $this->uid = $uid;

        return $this;
    }
}