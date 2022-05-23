<?php

declare(strict_types=1);

namespace BePaidAcquiring\DTO\Subscription;

use BePaidAcquiring\DTO\BaseDto;
use DateTimeImmutable;

class Subscription extends BaseDto
{
    private ?Card $card = null;
    private ?DateTimeImmutable $created_at = null;
    private ?Customer $customer = null;
    private ?string $id = null;
    private ?PlanObj $plan = null;
    private ?DateTimeImmutable $renew_at = null;
    private ?string $state = null;
    private ?string $tracking_id = null;
    private ?Transaction $transaction = null;

    public static function createFromArray(array $fields): Subscription
    {
        $dto = new Subscription();
        $dto->setCard(isset($fields['card']) ? Card::createFromArray($fields['card']) : null);
        $dto->setCreatedAt(isset($fields['created_at']) ? self::toDateTime($fields['created_at']) : null);
        $dto->setCustomer(isset($fields['customer']) ? Customer::createFromArray($fields['customer']) : null);
        $dto->setId($fields['id'] ?? null);
        $dto->setPlan(isset($fields['plan']) ? PlanObj::createFromArray($fields['plan']) : null);
        $dto->setRenewAt(isset($fields['renew_at']) ? self::toDateTime($fields['renew_at']) : null);
        $dto->setState($fields['state'] ?? null);
        $dto->setTrackingId($fields['tracking_id'] ?? null);
        $dto->setTransaction(isset($fields['transaction']) ? Transaction::createFromArray($fields['transaction']) : null);

        return $dto;
    }

    /**
     * @return Card|null
     */
    public function getCard(): ?Card
    {
        return $this->card;
    }

    /**
     * @param Card|null $card
     * @return Subscription
     */
    public function setCard(?Card $card): Subscription
    {
        $this->card = $card;

        return $this;
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
     * @return Subscription
     */
    public function setCreatedAt(?DateTimeImmutable $created_at): Subscription
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Customer|null
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer|null $customer
     * @return Subscription
     */
    public function setCustomer(?Customer $customer): Subscription
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     * @return Subscription
     */
    public function setId(?string $id): Subscription
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return PlanObj|null
     */
    public function getPlan(): ?PlanObj
    {
        return $this->plan;
    }

    /**
     * @param PlanObj|null $plan
     * @return Subscription
     */
    public function setPlan(?PlanObj $plan): Subscription
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getRenewAt(): ?DateTimeImmutable
    {
        return $this->renew_at;
    }

    /**
     * @param DateTimeImmutable|null $renew_at
     * @return Subscription
     */
    public function setRenewAt(?DateTimeImmutable $renew_at): Subscription
    {
        $this->renew_at = $renew_at;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string|null $state
     * @return Subscription
     */
    public function setState(?string $state): Subscription
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTrackingId(): ?string
    {
        return $this->tracking_id;
    }

    /**
     * @param string|null $tracking_id
     * @return Subscription
     */
    public function setTrackingId(?string $tracking_id): Subscription
    {
        $this->tracking_id = $tracking_id;

        return $this;
    }

    /**
     * @return Transaction|null
     */
    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }

    /**
     * @param Transaction|null $transaction
     * @return Subscription
     */
    public function setTransaction(?Transaction $transaction): Subscription
    {
        $this->transaction = $transaction;

        return $this;
    }
}
