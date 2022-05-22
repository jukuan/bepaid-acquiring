<?php

declare(strict_types=1);

namespace BePaidAcquiring\Request;

class SubscriptionRequest
{
    private string $notificationUrl;
    private string $title;
    private string $currency;
    private string $languageCode;
    private int $shopId;
    private int $amount;
    private int $planInterval = 1;
    private string $intervalUnit = 'month';

    public function __construct(
        string $notificationUrl,
        string $title,
        int $shopId,
        int $amount,
        string $languageCode = 'be',
        string $currency = 'BYN',
        int $planInterval = 1,
        string $intervalUnit = 'month'
    ) {
        $this->notificationUrl = $notificationUrl;
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
        return [
            'notification_url' => $this->notificationUrl,
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
    }
}
