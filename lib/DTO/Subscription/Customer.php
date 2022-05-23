<?php

declare(strict_types=1);

namespace BePaidAcquiring\DTO\Subscription;

use BePaidAcquiring\DTO\BaseDto;

class Customer extends BaseDto
{
    private ?string $id = null;

    public static function createFromArray($fields): Customer
    {
        $dto = new Customer();
        $dto->setId($fields['id'] ?? null);

        return $dto;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }
}
