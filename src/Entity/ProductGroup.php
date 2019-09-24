<?php

namespace Shapecode\SubscriptionBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Shapecode\SubscriptionBundle\Model\ProductGroupInterface;
use Shapecode\SubscriptionBundle\Model\ProductInterface;

/**
 * Class Product
 *
 * @package App\Entity
 * @author  Nikita Loges
 *
 * @ORM\Entity(repositoryClass="App\Repository\ProductGroupRepository")
 */
class ProductGroup implements ProductGroupInterface
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
     * @ORM\OneToMany(targetEntity="Shapecode\SubscriptionBundle\Model\ProductInterface", mappedBy="group")
     */
    protected $products;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param ProductInterface $product
     *
     * @return bool
     */
    public function hasProduct(ProductInterface $product)
    {
        return $this->products->contains($product);
    }

    /**
     * @param ProductInterface $product
     */
    public function addProduct(ProductInterface $product)
    {
        if (!$this->hasProduct($product)) {
            $product->setGroup($this);
            $this->products->add($product);
        }
    }

    /**
     * @param ProductInterface $product
     */
    public function removeProduct(ProductInterface $product)
    {
        if ($this->hasProduct($product)) {
            $product->setGroup(null);
            $this->products->removeElement($product);
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName() ?: 'No name';
    }
}
