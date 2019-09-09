<?php

namespace Shapecode\SubscriptionBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;

/**
 * Interface FeatureInterface
 *
 * @package Shapecode\SubscriptionBundle\Model
 * @author  Nikita Loges
 */
interface FeatureInterface
{

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getKey(): string;

    /**
     * @return ArrayCollection|PersistentCollection|Collection|ProductInterface[]
     */
    public function getProducts(): Collection;

    /**
     * @return ArrayCollection|PersistentCollection|Collection|AddonInterface[]
     */
    public function getAddons(): Collection;

    /**
     * @return ArrayCollection|PersistentCollection|Collection|SubscriptionInterface[]
     */
    public function getSubscriptions(): Collection;

}
