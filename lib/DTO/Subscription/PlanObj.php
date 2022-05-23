<?php

declare(strict_types=1);

namespace BePaidAcquiring\DTO\Subscription;

use BePaidAcquiring\DTO\BaseDto;

class PlanObj extends BaseDto
{
    private ?string $currency = null;
    private ?string $id = null;
    private ?Plan $plan = null;
    private ?string $title = null;
    private ?Plan $trial = null;

    public static function createFromArray(array $fields): PlanObj
    {
        $dto = new PlanObj();
        $dto->setCurrency($fields['currency'] ?? null);
        $dto->setId($fields['id'] ?? null);
        $dto->setPlan(isset($fields['plan']) ? Plan::createFromArray($fields['plan']) : null);
        $dto->setTitle($fields['title'] ?? null);
        $dto->setTrial(isset($fields['trial']) ? Plan::createFromArray($fields['trial']) : null);

        return $dto;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string|null $currency
     * @return PlanObj
     */
    public function setCurrency(?string $currency): PlanObj
    {
        $this->currency = $currency;

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
     * @return PlanObj
     */
    public function setId(?string $id): PlanObj
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Plan|null
     */
    public function getPlan(): ?Plan
    {
        return $this->plan;
    }

    /**
     * @param Plan|null $plan
     * @return PlanObj
     */
    public function setPlan(?Plan $plan): PlanObj
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return PlanObj
     */
    public function setTitle(?string $title): PlanObj
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Plan|null
     */
    public function getTrial(): ?Plan
    {
        return $this->trial;
    }

    /**
     * @param Plan|null $trial
     * @return PlanObj
     */
    public function setTrial(?Plan $trial): PlanObj
    {
        $this->trial = $trial;

        return $this;
    }
}
