<?php

namespace Shapecode\SubscriptionBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Shapecode\SubscriptionBundle\Model\AddonInterface;
use Shapecode\SubscriptionBundle\Model\FeatureInterface;
use Shapecode\SubscriptionBundle\Model\ProductInterface;
use Shapecode\SubscriptionBundle\Model\SubscriptionInterface;
use Tenolo\Bundle\EntityBundle\Entity\BaseEntity;

/**
 * Class Feature
 *
 * @package Shapecode\SubscriptionBundle\Entity
 * @author  Nikita Loges
 *
 * @ORM\Entity
 */
class Feature implements FeatureInterface
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
     * @ORM\ManyToMany(targetEntity="Shapecode\SubscriptionBundle\Model\ProductInterface", mappedBy="features")
     */
    protected $products;

    /**
     * @var ArrayCollection|PersistentCollection|Collection|SubscriptionInterface[]
     * @ORM\ManyToMany(targetEntity="Shapecode\SubscriptionBundle\Model\SubscriptionInterface", mappedBy="product")
     */
    protected $subscriptions;

    /**
     * @var ArrayCollection|PersistentCollection|Collection|AddonInterface[]
     * @ORM\ManyToMany(targetEntity="Shapecode\SubscriptionBundle\Model\AddonInterface", mappedBy="features")
     */
    protected $addons;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $key;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $value;

    /**
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->subscriptions = new ArrayCollection();
        $this->addons = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
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
    public function getAddons(): Collection
    {
        return $this->addons;
    }

    /**
     * @inheritDoc
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

}
