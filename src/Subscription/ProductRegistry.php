<?php

namespace Shapecode\SubscriptionBundle\Subscription;

use Shapecode\SubscriptionBundle\Exception\StrategyNotFoundException;
use Shapecode\SubscriptionBundle\Strategy\ProductStrategyInterface;

/**
 * Class ProductRegistry
 *
 * @package Shapecode\SubscriptionBundle\Subscription
 * @author  Nikita Loges
 */
class ProductRegistry
{

    /** @var ProductStrategyInterface[] */
    protected $strategies = [];

    /** @var SubscriptionConfig */
    protected $config;

    /**
     * @param SubscriptionConfig $config
     */
    public function __construct(SubscriptionConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Add strategy.
     *
     * @param ProductStrategyInterface $strategy
     *
     * @throws \InvalidArgumentException
     */
    public function addStrategy(ProductStrategyInterface $strategy): void
    {
        $name = $strategy->getShortName();

        if (array_key_exists($name, $this->strategies)) {
            throw new \InvalidArgumentException(sprintf('The strategy %s is already a registered strategy.', $name));
        }

        $this->strategies[$name] = $strategy;
    }

    /**
     * Get strategy.
     *
     * @param string $name
     *
     * @return ProductStrategyInterface
     *
     * @throws StrategyNotFoundException
     */
    public function get($name): ProductStrategyInterface
    {
        if (!array_key_exists($name, $this->strategies)) {
            throw new StrategyNotFoundException(sprintf('The strategy "%s" does not exist in the registry', $name));
        }

        return $this->strategies[$name];
    }

    /**
     * @return ProductStrategyInterface
     * @throws StrategyNotFoundException
     */
    public function getDefault(): ProductStrategyInterface
    {
        return $this->get($this->config->getDefaultProductStrategy());
    }
}
