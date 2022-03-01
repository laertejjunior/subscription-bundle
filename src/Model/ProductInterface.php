<?php

namespace Laertejjunior\SubscriptionBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;

/**
 * ProductInterface
 */
interface ProductInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return ArrayCollection|PersistentCollection|Collection|FeatureInterface[]
     */
    public function getFeatures(): Collection;

    /**
     * @return ProductGroupInterface|null
     */
    public function getGroup(): ?ProductGroupInterface;

    /**
     * @param ProductGroupInterface|null $group
     */
    public function setGroup(?ProductGroupInterface $group): self;

    /**
     * @return \DateInterval
     */
    public function getDuration();

    /**
     * @return \DateTimeInterface
     */
    public function getExpirationDate();

    /**
     * @return integer
     */
    public function getQuota();

    /**
     * @return boolean
     */
    public function isAutoRenewal();

    /**
     * @return boolean
     */
    public function isDefault();

    /**
     * @return string
     */
    public function getStrategy();

    /**
     * @return ProductInterface
     */
    public function getNextRenewalProduct();
}
