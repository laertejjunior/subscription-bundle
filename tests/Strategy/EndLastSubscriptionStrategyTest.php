<?php

namespace Laertejjunior\SubscriptionBundle\Tests\Strategy;

use PHPUnit\Framework\TestCase;
use Laertejjunior\SubscriptionBundle\Exception\PermanentSubscriptionException;
use Laertejjunior\SubscriptionBundle\Model\ProductInterface;
use Laertejjunior\SubscriptionBundle\Model\SubscriptionInterface;
use Laertejjunior\SubscriptionBundle\Strategy\ProductDefaultStrategy;
use Laertejjunior\SubscriptionBundle\Strategy\SubscriptionEndLastStrategy;
use Laertejjunior\SubscriptionBundle\Tests\AbstractTestCaseBase;
use Laertejjunior\SubscriptionBundle\Tests\Mock\SubscriptionMock;

class EndLastSubscriptionStrategyTest extends AbstractTestCaseBase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testExpiredSubscription()
    {
        $currentDate = new \DateTime();
        $period      = 864000; // 10 days

        // Product
        $product = \Mockery::mock(ProductInterface::class);
        $product->shouldReceive('isAutoRenewal')->andReturn(false);
        $product->shouldReceive('getDuration')->andReturn($period);

        // Active subscriptions
        $subscription1 = \Mockery::mock(SubscriptionInterface::class);
        $subscription1->shouldReceive('getEndDate')->andReturn(new \DateTime('2017-04-15 16:05:10'));

        // Strategy
        $strategy     = new SubscriptionEndLastStrategy(SubscriptionMock::class, $this->defaultProductStrategy);
        $subscription = $strategy->createSubscription($product, [$subscription1]);

        $this->assertEquals($currentDate->getTimestamp(), $subscription->getStartDate()->getTimestamp());
        $this->assertEquals(
            $currentDate->modify('+'.$period.' seconds')->getTimestamp(),
            $subscription->getEndDate()->getTimestamp()
        );
    }

    public function testNonExpiredSubscription()
    {
        $currentDate = new \DateTime();
        $period      = 864000; // 10 days

        // Product
        $product = \Mockery::mock(ProductInterface::class);
        $product->shouldReceive('isAutoRenewal')->andReturn(false);
        $product->shouldReceive('getDuration')->andReturn($period);

        // Active subscriptions
        $subscription1 = \Mockery::mock(SubscriptionInterface::class);
        $subscription1->shouldReceive('getEndDate')->andReturn($currentDate->modify('+5 days'));

        // Strategy
        $strategy     = new SubscriptionEndLastStrategy(SubscriptionMock::class, $this->defaultProductStrategy);
        $subscription = $strategy->createSubscription($product, [$subscription1]);

        $this->assertEquals($currentDate->modify('+5 days')->getTimestamp(), $subscription->getStartDate()->getTimestamp());
        $this->assertEquals(
            $currentDate->modify('+15 days')->getTimestamp(),
            $subscription->getEndDate()->getTimestamp()
        );
    }

    public function testCreatePermanentSubscriptionOnNoActiveSubscriptions()
    {
        // Product
        $product = \Mockery::mock(ProductInterface::class);
        $product->shouldReceive('isAutoRenewal')->andReturn(false);
        $product->shouldReceive('getDuration')->andReturn(null);

        $strategy     = new SubscriptionEndLastStrategy(SubscriptionMock::class, $this->defaultProductStrategy);
        $subscription = $strategy->createSubscription($product);

        $this->assertEquals(null, $subscription->getEndDate());
    }

    public function testFailOnMoreThanOnePermanentSubscriptionByProduct()
    {
        $this->expectException(PermanentSubscriptionException::class);

        // Active subscriptions
        $subscription1 = \Mockery::mock(SubscriptionInterface::class);
        $subscription1->shouldReceive('getName')->andReturn('Subscription one with product A');
        $subscription1->shouldReceive('getProduct')->andReturn($this->product);
        $subscription1->shouldReceive('getEndDate')->andReturn(null);

        $subscription2 = \Mockery::mock(SubscriptionInterface::class);
        $subscription2->shouldReceive('getName')->andReturn('Subscription two with product A');
        $subscription2->shouldReceive('getProduct')->andReturn($this->product);
        $subscription2->shouldReceive('getEndDate')->andReturn(null);

        $strategy = new SubscriptionEndLastStrategy(SubscriptionMock::class, $this->defaultProductStrategy);
        $strategy->createSubscription($this->product, [$subscription1, $subscription2]);
    }

    public function testReturnSameSubscriptionInstanceOnPermanentSubscription()
    {
        // Product X
        $productX = \Mockery::mock(ProductInterface::class);

        // Active subscriptions
        $subscription1 = \Mockery::mock(SubscriptionInterface::class);
        $subscription1->shouldReceive('getName')->andReturn('Subscription two with product X');
        $subscription1->shouldReceive('getProduct')->andReturn($productX);
        $subscription1->shouldReceive('getEndDate')->andReturn(null);

        $strategy     = new SubscriptionEndLastStrategy(SubscriptionMock::class, $this->defaultProductStrategy);
        $subscription = $strategy->createSubscription($this->product, [$subscription1]);

        $this->assertEquals($subscription1, $subscription);
    }
}
