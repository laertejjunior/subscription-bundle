<?php

namespace Laertejjunior\SubscriptionBundle\Subscription;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;
use Laertejjunior\SubscriptionBundle\Model\AddonInterface;
use Laertejjunior\SubscriptionBundle\Model\FeatureInterface;
use Laertejjunior\SubscriptionBundle\Model\ProductGroupInterface;
use Laertejjunior\SubscriptionBundle\Model\ProductInterface;
use Laertejjunior\SubscriptionBundle\Model\SubscriptionInterface;
use Laertejjunior\SubscriptionBundle\Repository\ProductGroupRepositoryInterface;
use Laertejjunior\SubscriptionBundle\Repository\ProductRepositoryInterface;
use Laertejjunior\SubscriptionBundle\Repository\SubscriptionRepositoryInterface;

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
        return $this->getSubscriptionRepository()->getClassName();
    }

    /**
     * @return SubscriptionRepositoryInterface
     */
    public function getSubscriptionRepository(): SubscriptionRepositoryInterface
    {
        return $this->registry->getRepository(SubscriptionInterface::class);
    }

    /**
     * @return string
     */
    public function getProductClass(): string
    {
        return $this->getProductRepository()->getClassName();
    }

    /**
     * @return ProductRepositoryInterface
     */
    public function getProductRepository(): ProductRepositoryInterface
    {
        return $this->registry->getRepository(ProductInterface::class);
    }

    /**
     * @return string
     */
    public function getFeatureClass(): string
    {
        return $this->getFeatureRepository()->getClassName();
    }

    /**
     * @return EntityRepository
     */
    public function getFeatureRepository(): EntityRepository
    {
        return $this->registry->getRepository(FeatureInterface::class);
    }

    /**
     * @return string
     */
    public function getAddonClass(): string
    {
        return $this->getAddonRepository()->getClassName();
    }

    /**
     * @return EntityRepository
     */
    public function getAddonRepository(): EntityRepository
    {
        return $this->registry->getRepository(AddonInterface::class);
    }

    /**
     * @return string
     */
    public function getProductGroupClass(): string
    {
        return $this->getProductGroupRepository()->getClassName();
    }

    /**
     * @return ProductGroupRepositoryInterface
     */
    public function getProductGroupRepository(): ProductGroupRepositoryInterface
    {
        return $this->registry->getRepository(ProductGroupInterface::class);
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

    /**
     * @return bool|null
     */
    public function getFeatureCheckOverride(): ?bool
    {
        return $this->config['feature_check_override'];
    }
}
