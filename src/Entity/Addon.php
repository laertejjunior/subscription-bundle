<?php

namespace Laertejjunior\SubscriptionBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Laertejjunior\SubscriptionBundle\Model\AddonInterface;
use Laertejjunior\SubscriptionBundle\Model\FeatureInterface;
use Laertejjunior\SubscriptionBundle\Model\ProductInterface;
use Laertejjunior\SubscriptionBundle\Model\SubscriptionInterface;

/**
 * Class ProductAddon
 *
 * @package Laertejjunior\SubscriptionBundle\Entity
 * @author  Nikita Loges
 *
 * @ORM\Entity
 */
class Addon implements AddonInterface
{

    /**
     * @var int
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection|PersistentCollection|Collection|ProductInterface[]
     * @ORM\ManyToMany(targetEntity="Laertejjunior\SubscriptionBundle\Model\ProductInterface", mappedBy="addons")
     */
    protected $products;

    /**
     * @var ArrayCollection|PersistentCollection|Collection|SubscriptionInterface[]
     * @ORM\ManyToMany(targetEntity="Laertejjunior\SubscriptionBundle\Model\SubscriptionInterface", mappedBy="addons")
     */
    protected $subscriptions;

    /**
     * @var ArrayCollection|PersistentCollection|Collection|FeatureInterface[]
     * @ORM\ManyToMany(targetEntity="Laertejjunior\SubscriptionBundle\Model\FeatureInterface", inversedBy="addons")
     */
    protected $features;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
        $this->features = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    /**
     * @inheritDoc
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    /**
     * @inheritDoc
     */
    public function getFeatures(): Collection
    {
        return $this->features;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName() ?: 'No name';
    }

}
