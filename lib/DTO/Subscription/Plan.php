<?php

declare(strict_types=1);

namespace BePaidAcquiring\DTO\Subscription;

use BePaidAcquiring\DTO\BaseDto;

class Plan extends BaseDto
{
    private ?int $amount = null;
    private ?int $interval = null;
    private ?string $interval_unit = null;

    public static function createFromArray($fields): Plan
    {
        if (!is_array($fields)) {
            return new static();
        }

        $dto = new Plan();
        $dto->setAmount($fields['amount'] ?? null);
        $dto->setInterval($fields['interval'] ?? null);
        $dto->setIntervalUnit($fields['interval_unit'] ?? null);

        return $dto;
    }

    /**
     * @return int|null
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @return int|null
     */
    public function getInterval(): ?int
    {
        return $this->interval;
    }

    /**
     * @return string|null
     */
    public function getIntervalUnit(): ?string
    {
        return $this->interval_unit;
    }

    /**
     * @param int|null $amount
     * @return Plan
     */
    public function setAmount(?int $amount): Plan
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @param int|null $interval
     * @return Plan
     */
    public function setInterval(?int $interval): Plan
    {
        $this->interval = $interval;

        return $this;
    }

    /**
     * @param string|null $interval_unit
     * @return Plan
     */
    public function setIntervalUnit(?string $interval_unit): Plan
    {
        $this->interval_unit = $interval_unit;

        return $this;
    }

    public function isValid(): bool
    {
        return $this->amount > 0 &&
            $this->interval > 0 &&
            null !== $this->interval_unit;
    }
}
