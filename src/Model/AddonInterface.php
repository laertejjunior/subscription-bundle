<?php

namespace Laertejjunior\SubscriptionBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;

/**
 * Interface AddonInterface
 *
 * @package Laertejjunior\SubscriptionBundle\Model
 * @author  Nikita Loges
 */
interface AddonInterface
{

    /**
     * @return string
     */
    public function getName(): ?string;

    /**
     * @return ArrayCollection|PersistentCollection|Collection|ProductInterface[]
     */
    public function getProducts(): Collection;

    /**
     * @return ArrayCollection|PersistentCollection|Collection|SubscriptionInterface[]
     */
    public function getSubscriptions(): Collection;

    /**
     * @return ArrayCollection|PersistentCollection|Collection|FeatureInterface[]
     */
    public function getFeatures(): Collection;
}
