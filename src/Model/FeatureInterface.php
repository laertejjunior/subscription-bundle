<?php

namespace Laertejjunior\SubscriptionBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;

/**
 * Interface FeatureInterface
 *
 * @package Laertejjunior\SubscriptionBundle\Model
 * @author  Nikita Loges
 */
interface FeatureInterface
{

    /**
     * @return string
     */
    public function getName(): ?string;

    /**
     * @return string
     */
    public function getKey(): ?string;

    /**
     * @return ArrayCollection|PersistentCollection|Collection|ProductInterface[]
     */
    public function getProducts(): Collection;

    /**
     * @return ArrayCollection|PersistentCollection|Collection|AddonInterface[]
     */
    public function getAddons(): Collection;

    /**
     * @return int|null
     */
    public function getPosition(): ?int;

    /**
     * @param int|null $position
     */
    public function setPosition(?int $position): void;

}
