<?php

declare(strict_types=1);

namespace BePaidAcquiring\DTO\Subscription;

use BePaidAcquiring\DTO\BaseDto;
use DateTimeImmutable;

class Subscription extends BaseDto
{
    private ?string $id = null;
    private ?string $state = null;
    private ?string $tracking_id = null;
    private ?string $device_id = null;
    private ?int $paid_billing_cycles = null;
    private ?int $number_failed_payment_attempts = null;

    private ?Card $card = null;
    private ?Customer $customer = null;
    private ?PlanObj $plan = null;

    private ?Transaction $transaction = null;
    private ?Transaction $lastTransaction = null;

    private ?DateTimeImmutable $created_at = null;
    private ?DateTimeImmutable $renew_at = null;
    private ?DateTimeImmutable $active_to = null;

    public static function createFromArray($fields): Subscription
    {
        if (!is_array($fields)) {
            return new static();
        }

        $dto = new Subscription();
        $dto->setId($fields['id'] ?? null);
        $dto->setState($fields['state'] ?? null);
        $dto->setTrackingId($fields['tracking_id'] ?? null);
        $dto->setDeviceId($fields['device_id'] ?? null);
        $dto->setPaidBillingCycles($fields['paid_billing_cycles'] ?? null);
        $dto->setNumberFailedPaymentAttempts($fields['number_failed_payment_attempts'] ?? null);

        $dto->setCard(isset($fields['card']) ? Card::createFromArray($fields['card']) : null);
        $dto->setCustomer(isset($fields['customer']) ? Customer::createFromArray($fields['customer']) : null);
        $dto->setPlan(isset($fields['plan']) ? PlanObj::createFromArray($fields['plan']) : null);

        $dto->setTransaction(isset($fields['transaction']) ? Transaction::createFromArray($fields['transaction']) : null);
        $dto->setLastTransaction(isset($fields['last_transaction']) ? Transaction::createFromArray($fields['last_transaction']) : null);

        $dto->setCreatedAt(isset($fields['created_at']) ? self::toDateTime($fields['created_at']) : null);
        $dto->setRenewAt(isset($fields['renew_at']) ? self::toDateTime($fields['renew_at']) : null);
        $dto->setActiveTo(isset($fields['active_to']) ? self::toDateTime($fields['active_to']) : null);

        return $dto;
    }

    public function getCard(): ?Card
    {
        return $this->card;
    }

    public function setCard(?Card $card): Subscription
    {
        $this->card = $card;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(?DateTimeImmutable $created_at): Subscription
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): Subscription
    {
        $this->customer = $customer;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): Subscription
    {
        $this->id = $id;

        return $this;
    }

    public function getPlan(): ?PlanObj
    {
        return $this->plan;
    }

    public function setPlan(?PlanObj $plan): Subscription
    {
        $this->plan = $plan;

        return $this;
    }

    public function getRenewAt(): ?DateTimeImmutable
    {
        return $this->renew_at;
    }

    public function setRenewAt(?DateTimeImmutable $renew_at): Subscription
    {
        $this->renew_at = $renew_at;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function isActiveState(): bool
    {
        return 'active' === $this->state;
    }

    public function setState(?string $state): Subscription
    {
        $this->state = $state;

        return $this;
    }

    public function getTrackingId(): ?string
    {
        return $this->tracking_id;
    }

    public function setTrackingId(?string $tracking_id): Subscription
    {
        $this->tracking_id = $tracking_id;

        return $this;
    }

    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }

    public function setTransaction(?Transaction $transaction): Subscription
    {
        $this->transaction = $transaction;

        return $this;
    }

    public function getLastTransaction(): ?Transaction
    {
        return $this->lastTransaction;
    }

    public function setLastTransaction(?Transaction $lastTransaction): Subscription
    {
        $this->lastTransaction = $lastTransaction;

        return $this;
    }

    public function getCardToken(): ?string
    {
        if (null === $this->card) {
            return null;
        }

        return $this->card->getToken();
    }

    public function getPlanId(): ?string
    {
        if (null === $this->plan) {
            return null;
        }

        return $this->plan->getId();
    }

    public function getPlanAmount(): ?int
    {
        if (null === $this->plan || null === $this->plan->getPlan()) {
            return null;
        }

        return $this->plan->getPlan()->getAmount();
    }

    public function getExpiration() : ?string
    {
        if (null === $this->card) {
            return null;
        }

        return sprintf('%s-%s', $this->card->getExpYear(), $this->card->getExpMonth());
    }

    public function getCardHolder() : ?string
    {
        if (null === $this->card) {
            return null;
        }

        return $this->card->getHolder();
    }

    public function getCardLastDigits() : ?string
    {
        if (null === $this->card) {
            return null;
        }

        return $this->card->getLast4();
    }

    public function getTransactionStatus() : ?string
    {
        if (null === $this->transaction) {
            return null;
        }

        return $this->transaction->getStatus();
    }

    public function getLastTransactionStatus() : ?string
    {
        if (null === $this->lastTransaction) {
            return null;
        }

        return $this->lastTransaction->getStatus();
    }

    public function getDeviceId(): ?string
    {
        return $this->device_id;
    }

    public function setDeviceId(?string $device_id): Subscription
    {
        $this->device_id = $device_id;

        return $this;
    }

    public function getPaidBillingCycles(): ?int
    {
        return $this->paid_billing_cycles;
    }

    public function setPaidBillingCycles(?int $paid_billing_cycles): Subscription
    {
        $this->paid_billing_cycles = $paid_billing_cycles;

        return $this;
    }

    public function getNumberFailedPaymentAttempts(): ?int
    {
        return $this->number_failed_payment_attempts;
    }

    public function setNumberFailedPaymentAttempts(?int $number_failed_payment_attempts): Subscription
    {
        $this->number_failed_payment_attempts = $number_failed_payment_attempts;

        return $this;
    }

    public function getActiveTo(): ?DateTimeImmutable
    {
        return $this->active_to;
    }

    public function setActiveTo(?DateTimeImmutable $active_to): Subscription
    {
        $this->active_to = $active_to;

        return $this;
    }

    public function isValid(): bool
    {
        return null !== $this->getId() &&
            null !== $this->plan &&
            $this->plan->isValid();
    }
}
