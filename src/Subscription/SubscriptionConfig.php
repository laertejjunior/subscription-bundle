<?php

namespace Shapecode\SubscriptionBundle\Subscription;

use Doctrine\Common\Persistence\ManagerRegistry;
use Shapecode\SubscriptionBundle\Repository\ProductGroupRepositoryInterface;
use Shapecode\SubscriptionBundle\Repository\ProductRepositoryInterface;
use Shapecode\SubscriptionBundle\Repository\SubscriptionRepositoryInterface;

/**
 * Class SubscriptionConfig
 *
 * @package shapecode\SubscriptionBundle\Subscription
 * @author  Nikita Loges
 */
class SubscriptionConfig
{

    /** @var ManagerRegistry */
    protected $registry;

    /** @var array */
    protected $config;

    /**
     * @param ManagerRegistry $registry
     * @param array           $bundleConfig
     */
    public function __construct(
        ManagerRegistry $registry,
        array $bundleConfig
    ) {
        $this->registry = $registry;
        $this->config = $bundleConfig;
    }

    /**
     * @return string
     */
    public function getSubscriptionClass(): string
    {
        return $this->config['subscription_class'];
    }

    /**
     * @return SubscriptionRepositoryInterface
     */
    public function getSubscriptionRepository(): SubscriptionRepositoryInterface
    {
        return $this->registry->getRepository($this->getSubscriptionClass());
    }

    /**
     * @return string
     */
    public function getProductClass(): string
    {
        return $this->config['product_class'];
    }

    /**
     * @return ProductRepositoryInterface
     */
    public function getProductRepository(): ProductRepositoryInterface
    {
        return $this->registry->getRepository($this->getProductClass());
    }

    /**
     * @return string
     */
    public function getProductGroupClass(): string
    {
        return $this->config['product_group_class'];
    }

    /**
     * @return ProductGroupRepositoryInterface
     */
    public function getProductGroupRepository(): ProductGroupRepositoryInterface
    {
        return $this->registry->getRepository($this->getProductGroupClass());
    }

    /**
     * @param $reason
     *
     * @return string
     */
    public function getReason($reason): string
    {
        return $this->config['reasons'][$reason];
    }

    /**
     * @return string
     */
    public function getDefaultProductStrategy(): string
    {
        return $this->config['default_product_strategy'];
    }

    /**
     * @return string
     */
    public function getDefaultSubscriptionStrategy(): string
    {
        return $this->config['default_subscription_strategy'];
    }
}
