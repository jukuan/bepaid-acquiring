<?php

declare(strict_types=1);

namespace BePaidAcquiring\DTO\Subscription;

use BePaidAcquiring\DTO\BaseDto;

class Card extends BaseDto
{
    private ?string $token = null;
    private ?string $holder = null;
    private ?string $stamp = null;
    private ?string $brand = null;
    private ?string $last_4 = null;
    private ?string $first_1 = null;
    private ?string $bin = null;
    private ?string $issuer_country = null;
    private ?string $issuer_name = null;
    private ?string $product = null;
    private ?string $token_provider = null;
    private ?int $exp_month = null;
    private ?int $exp_year = null;

    public static function createFromArray($fields): Card
    {
        if (!is_array($fields)) {
            return new static();
        }

        $dto = new Card();
        $dto->setToken(isset($fields['token']) ? $fields['token'] : null);
        $dto->setHolder(isset($fields['holder']) ? $fields['holder'] : null);
        $dto->setStamp(isset($fields['stamp']) ? $fields['stamp'] : null);
        $dto->setBrand(isset($fields['brand']) ? $fields['brand'] : null);
        $dto->setLast4(isset($fields['last_4']) ? $fields['last_4'] : null);
        $dto->setFirst1(isset($fields['first_1']) ? $fields['first_1'] : null);
        $dto->setBin(isset($fields['bin']) ? $fields['bin'] : null);
        $dto->setIssuerCountry(isset($fields['issuer_country']) ? $fields['issuer_country'] : null);
        $dto->setIssuerName(isset($fields['issuer_name']) ? $fields['issuer_name'] : null);
        $dto->setProduct(isset($fields['product']) ? $fields['product'] : null);
        $dto->setTokenProvider(isset($fields['token_provider']) ? $fields['token_provider'] : null);
        $dto->setExpMonth(isset($fields['exp_month']) ? $fields['exp_month'] : null);
        $dto->setExpYear(isset($fields['exp_year']) ? $fields['exp_year'] : null);

        return $dto;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @return string|null
     */
    public function getHolder(): ?string
    {
        return $this->holder;
    }

    /**
     * @return string|null
     */
    public function getStamp(): ?string
    {
        return $this->stamp;
    }

    /**
     * @return string|null
     */
    public function getBrand(): ?string
    {
        return $this->brand;
    }

    /**
     * @return string|null
     */
    public function getLast4(): ?string
    {
        return $this->last_4;
    }

    /**
     * @return string|null
     */
    public function getFirst1(): ?string
    {
        return $this->first_1;
    }

    /**
     * @return string|null
     */
    public function getBin(): ?string
    {
        return $this->bin;
    }

    /**
     * @return string|null
     */
    public function getIssuerCountry(): ?string
    {
        return $this->issuer_country;
    }

    /**
     * @return string|null
     */
    public function getIssuerName(): ?string
    {
        return $this->issuer_name;
    }

    /**
     * @return string|null
     */
    public function getProduct(): ?string
    {
        return $this->product;
    }

    /**
     * @return string|null
     */
    public function getTokenProvider(): ?string
    {
        return $this->token_provider;
    }

    /**
     * @return int|null
     */
    public function getExpMonth(): ?int
    {
        return $this->exp_month;
    }

    /**
     * @return int|null
     */
    public function getExpYear(): ?int
    {
        return $this->exp_year;
    }

    /**
     * @param string|null $token
     * @return Card
     */
    public function setToken(?string $token): Card
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @param string|null $holder
     * @return Card
     */
    public function setHolder(?string $holder): Card
    {
        $this->holder = $holder;

        return $this;
    }

    /**
     * @param string|null $stamp
     * @return Card
     */
    public function setStamp(?string $stamp): Card
    {
        $this->stamp = $stamp;

        return $this;
    }

    /**
     * @param string|null $brand
     * @return Card
     */
    public function setBrand(?string $brand): Card
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @param string|null $last_4
     * @return Card
     */
    public function setLast4(?string $last_4): Card
    {
        $this->last_4 = $last_4;

        return $this;
    }

    /**
     * @param string|null $first_1
     * @return Card
     */
    public function setFirst1(?string $first_1): Card
    {
        $this->first_1 = $first_1;

        return $this;
    }

    /**
     * @param string|null $bin
     * @return Card
     */
    public function setBin(?string $bin): Card
    {
        $this->bin = $bin;

        return $this;
    }

    /**
     * @param string|null $issuer_country
     * @return Card
     */
    public function setIssuerCountry(?string $issuer_country): Card
    {
        $this->issuer_country = $issuer_country;

        return $this;
    }

    /**
     * @param string|null $issuer_name
     * @return Card
     */
    public function setIssuerName(?string $issuer_name): Card
    {
        $this->issuer_name = $issuer_name;

        return $this;
    }

    /**
     * @param string|null $product
     * @return Card
     */
    public function setProduct(?string $product): Card
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @param string|null $token_provider
     * @return Card
     */
    public function setTokenProvider(?string $token_provider): Card
    {
        $this->token_provider = $token_provider;

        return $this;
    }

    /**
     * @param int|null $exp_month
     * @return Card
     */
    public function setExpMonth(?int $exp_month): Card
    {
        $this->exp_month = $exp_month;

        return $this;
    }

    /**
     * @param int|null $exp_year
     * @return Card
     */
    public function setExpYear(?int $exp_year): Card
    {
        $this->exp_year = $exp_year;

        return $this;
    }
}
