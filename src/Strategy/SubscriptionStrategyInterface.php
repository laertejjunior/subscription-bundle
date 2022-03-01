<?php

namespace Laertejjunior\SubscriptionBundle\Strategy;

use Laertejjunior\SubscriptionBundle\Exception\PermanentSubscriptionException;
use Laertejjunior\SubscriptionBundle\Exception\StrategyNotFoundException;
use Laertejjunior\SubscriptionBundle\Model\ProductInterface;
use Laertejjunior\SubscriptionBundle\Model\SubscriptionInterface;

interface SubscriptionStrategyInterface
{
    /**
     * @param ProductInterface        $product       Product that will be used to create the new subscription
     * @param SubscriptionInterface[] $subscriptions Enabled subscriptions
     *
     * @return SubscriptionInterface
     *
     * @throws PermanentSubscriptionException
     */
    public function createSubscription(ProductInterface $product, array $subscriptions = []): SubscriptionInterface;

    /**
     * @return ProductStrategyInterface
     *
     * @throws StrategyNotFoundException
     */
    public function getProductStrategy(): ProductStrategyInterface;

    /**
     * @return string
     */
    public function getShortName(): string;
}
