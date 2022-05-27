<?php

declare(strict_types=1);

namespace BePaidAcquiring\Request;

class SubscriptionRequest
{
    private string $title;
    private string $currency;
    private string $languageCode;
    private int $shopId;
    private int $amount;
    private int $planInterval = 1;
    private string $intervalUnit = 'month';

    private string $notificationUrl = '';
    private string $return_url = '';
    private array $customer = [];

    public function __construct(
        string $title,
        int $shopId,
        int $amount,
        string $languageCode = 'be',
        string $currency = 'BYN',
        int $planInterval = 1,
        string $intervalUnit = 'month'
    ) {
        $this->title = $title;
        $this->shopId = $shopId;
        $this->amount = $amount;
        $this->languageCode = $languageCode;
        $this->currency = $currency;
        $this->planInterval = $planInterval;
        $this->intervalUnit = $intervalUnit;
    }

    public function toArray(): array
    {
        $request = [
            'plan' => [
                'currency' => $this->currency,
                'plan' => [
                    'amount' => $this->amount,
                    'interval' => $this->planInterval,
                    'interval_unit' => $this->intervalUnit,
                ],
                'shop_id' => $this->shopId,
                'title' => $this->title,
            ],
            'settings' => [
                'language' => $this->languageCode,
            ],
        ];

        if ('' !== $this->notificationUrl) {
            $request['notification_url'] = $this->notificationUrl;
        }

        if ('' !== $this->return_url) {
            $request['return_url'] = $this->return_url;
        }

        if (count($this->customer) > 0) {
            $request['customer'] = $this->customer;
        }

        return $request;
    }

    public function setNotificationUrl(string $notificationUrl): SubscriptionRequest
    {
        $this->notificationUrl = $notificationUrl;

        return $this;
    }

    public function setReturnUrl(string $return_url): SubscriptionRequest
    {
        $this->return_url = $return_url;

        return $this;
    }

    public function setCustomer(array $customer): SubscriptionRequest
    {
        $this->customer = $customer;

        return $this;
    }
}
