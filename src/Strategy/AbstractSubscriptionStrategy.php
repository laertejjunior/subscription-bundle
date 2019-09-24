<?php

namespace Shapecode\SubscriptionBundle\Strategy;

use Shapecode\SubscriptionBundle\Model\SubscriptionInterface;
use Shapecode\SubscriptionBundle\Subscription\ProductRegistry;
use Shapecode\SubscriptionBundle\Subscription\SubscriptionConfig;
use Shapecode\SubscriptionBundle\Subscription\SubscriptionRegistry;

/**
 * Class AbstractSubscriptionStrategy
 *
 * @package Shapecode\SubscriptionBundle\Strategy
 * @author  Nikita Loges
 */
abstract class AbstractSubscriptionStrategy implements SubscriptionStrategyInterface
{
    /** @var SubscriptionConfig */
    protected $config;

    /** @var SubscriptionRegistry */
    protected $subscriptionRegistry;

    /** @var ProductRegistry */
    protected $productRegistry;

    /** @var ProductStrategyInterface */
    protected $productStrategy;

    /**
     * @param SubscriptionConfig            $config
     * @param SubscriptionRegistry          $subscriptionRegistry
     * @param ProductRegistry               $productRegistry
     * @param ProductStrategyInterface|null $productStrategy
     */
    public function __construct(
        SubscriptionConfig $config,
        SubscriptionRegistry $subscriptionRegistry,
        ProductRegistry $productRegistry,
        ProductStrategyInterface $productStrategy = null
    ) {
        $this->config = $config;
        $this->subscriptionRegistry = $subscriptionRegistry;
        $this->productRegistry = $productRegistry;
        $this->productStrategy = $productStrategy;
    }

    /**
     * @return SubscriptionInterface
     */
    public function createSubscriptionInstance(): SubscriptionInterface
    {
        $class = $this->config->getSubscriptionClass();

        return new $class();
    }

    /**
     * @return ProductStrategyInterface
     */
    public function getProductStrategy(): ProductStrategyInterface
    {
        return $this->productStrategy ?? $this->productRegistry->getDefault($this->config->getDefaultProductStrategy());
    }

    /**
     * Create current date.
     *
     * @return \DateTime
     */
    protected function createCurrentDate(): \DateTime
    {
        return new \DateTime();
    }
}
