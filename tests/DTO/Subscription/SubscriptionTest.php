<?php

declare(strict_types=1);

namespace Tests\DTO\Subscription;

use BePaidAcquiring\DTO\Subscription\Subscription;
use Generator;
use PHPUnit\Framework\TestCase;
use stdClass;

class SubscriptionTest extends TestCase
{
    /**
     * @dataProvider subscriptionDataProvider()
     */
    public function testIncorrectData($data, ?string $expectedCreatedDate)
    {
        $subscription = Subscription::createFromArray($data);
        $createdAt = $subscription->getCreatedAt();

        $this->assertEquals($expectedCreatedDate, $createdAt ? $createdAt->format('Y-m-d') : null);
    }

    /**
     * @dataProvider subscriptionJsonProvider()
     */
    public function testSubscription(?string $json, ?string $expectedToken)
    {
        $data = null !== $json ? json_decode($json, true) : null;

        if (null !== $expectedToken && '' !== $expectedToken) {
            $this->assertIsArray($data);
        }

        $subscription = Subscription::createFromArray($data);
        $card = $subscription->getCard();

        $this->assertEquals($expectedToken, $card ? $card->getToken() : null);
    }

    public function subscriptionDataProvider(): Generator
    {
        yield 'Not full array' => [['created_at' => '1918-03-25T05:54:30.500Z'], '1918-03-25'];
        yield 'Empty array' => [[], null];
        yield 'Nullable data' => [null, null];
        yield 'Empty string' => ['', null];
        yield 'Not array, int' => [42, null];
        yield 'Not array, obj' => [new stdClass(), null];
    }

    public function subscriptionJsonProvider(): Generator
    {
        $out = file_get_contents(__DIR__.'/subscription_active.json');
        yield 'Active subscription' => [$out, '5559786942408b77017a3aac8390d46d77d181e34554df527a71919a856d0f28'];

        $out = file_get_contents(__DIR__.'/subscription_canceled.json');
        yield 'Canceled subscription' => [$out, '9990edb8e6f2af5d93a6259b690c50a7410bf9f97235f2e051345e01b580f699'];

        $out = file_get_contents(__DIR__.'/subscription_trial.json');
        yield 'Trial subscription' => [$out, '5559786942408b77017a3aac8390d46d77d181e34554df527a71919a856d0f28'];

        yield 'Nullable data' => [null, null];
        yield 'Empty string' => ['', null];
        yield 'Not array' => ['42', null];
    }
}
