<?php

namespace Shapecode\SubscriptionBundle\Subscription;

use Shapecode\SubscriptionBundle\Exception\StrategyNotFoundException;
use Shapecode\SubscriptionBundle\Strategy\SubscriptionStrategyInterface;

/**
 * Class SubscriptionRegistry
 *
 * @package Shapecode\SubscriptionBundle\Subscription
 * @author  Nikita Loges
 */
class SubscriptionRegistry
{
    /** @var SubscriptionStrategyInterface[] */
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
     * @param SubscriptionStrategyInterface $strategy
     *
     * @throws \InvalidArgumentException
     */
    public function addStrategy(SubscriptionStrategyInterface $strategy): void
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
     * @return SubscriptionStrategyInterface
     *
     * @throws StrategyNotFoundException
     */
    public function get($name): SubscriptionStrategyInterface
    {
        if (!array_key_exists($name, $this->strategies)) {
            throw new StrategyNotFoundException(sprintf('The strategy "%s" does not exist in the registry', $name));
        }

        return $this->strategies[$name];
    }

    /**
     * @return SubscriptionStrategyInterface
     * @throws StrategyNotFoundException
     */
    public function getDefault(): SubscriptionStrategyInterface
    {
        return $this->get($this->config->getDefaultSubscriptionStrategy());
    }
}
