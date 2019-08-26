<?php

namespace Shapecode\SubscriptionBundle\Strategy;

use Shapecode\SubscriptionBundle\Exception\PermanentSubscriptionException;
use Shapecode\SubscriptionBundle\Exception\StrategyNotFoundException;
use Shapecode\SubscriptionBundle\Model\ProductInterface;
use Shapecode\SubscriptionBundle\Model\SubscriptionInterface;

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
