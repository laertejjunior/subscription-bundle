<?php

namespace Laertejjunior\SubscriptionBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Laertejjunior\SubscriptionBundle\Model\AddonInterface;
use Laertejjunior\SubscriptionBundle\Model\FeatureInterface;
use Laertejjunior\SubscriptionBundle\Model\ProductInterface;

/**
 * Class Feature
 *
 * @package Laertejjunior\SubscriptionBundle\Entity
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
     * @ORM\ManyToMany(targetEntity="Laertejjunior\SubscriptionBundle\Model\ProductInterface", mappedBy="features", cascade={"persist"})
     */
    protected $products;

    /**
     * @var ArrayCollection|PersistentCollection|Collection|AddonInterface[]
     * @ORM\ManyToMany(targetEntity="Laertejjunior\SubscriptionBundle\Model\AddonInterface", mappedBy="features")
     */
    protected $addons;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string
     * @ORM\Column(type="string", name="keyword")
     */
    protected $key;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, name="factor")
     */
    protected $value;

    /**
     * @var integer|null
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $position;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default": true})
     */
    protected $display = true;

    /**
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->addons = new ArrayCollection();
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
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey(?string $key): void
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(?string $value): void
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
     * @param ProductInterface $product
     *
     * @return bool
     */
    public function hasProduct(ProductInterface $product): bool
    {
        return $this->products->contains($product);
    }

    /**
     * @param ProductInterface $product
     */
    public function addProduct(ProductInterface $product): void
    {
        if (!$this->hasProduct($product)) {
            $this->products->add($product);
            $product->getFeatures()->add($this);
        }
    }

    /**
     * @param ProductInterface $product
     */
    public function removeProduct(ProductInterface $product): void
    {
        if ($this->hasProduct($product)) {
            $this->products->removeElement($product);
            $product->getFeatures()->removeElement($this);
        }
    }

    /**
     * @inheritDoc
     */
    public function getAddons(): Collection
    {
        return $this->addons;
    }

    /**
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int|null $position
     */
    public function setPosition(?int $position): void
    {
        $this->position = $position;
    }

    /**
     * @return bool
     */
    public function isDisplay(): bool
    {
        return $this->display;
    }

    /**
     * @param bool $display
     */
    public function setDisplay(bool $display): void
    {
        $this->display = $display;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName() ?: 'No name';
    }

}
