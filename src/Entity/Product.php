<?php

namespace Laertejjunior\SubscriptionBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Laertejjunior\SubscriptionBundle\Model\AddonInterface;
use Laertejjunior\SubscriptionBundle\Model\FeatureInterface;
use Laertejjunior\SubscriptionBundle\Model\ProductGroupInterface;
use Laertejjunior\SubscriptionBundle\Model\ProductInterface;
use Laertejjunior\SubscriptionBundle\Model\SubscriptionInterface;

/**
 * Class Product
 *
 * @package App\Entity
 * @author  Nikita Loges
 *
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product implements ProductInterface
{

    /**
     * @var int
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ArrayCollection|PersistentCollection|Collection|SubscriptionInterface[]
     * @ORM\OneToMany(targetEntity="Laertejjunior\SubscriptionBundle\Model\SubscriptionInterface", mappedBy="product")
     */
    protected $subscriptions;

    /**
     * @var ArrayCollection|PersistentCollection|Collection|FeatureInterface[]
     * @ORM\ManyToMany(targetEntity="Laertejjunior\SubscriptionBundle\Model\FeatureInterface", inversedBy="products")
     * @ORM\OrderBy({"position": "ASC"})
     */
    protected $features;

    /**
     * @var ArrayCollection|PersistentCollection|Collection|AddonInterface[]
     * @ORM\ManyToMany(targetEntity="Laertejjunior\SubscriptionBundle\Model\AddonInterface", inversedBy="products")
     */
    protected $addons;

    /**
     * @var ProductInterface|null
     * @ORM\OneToOne(targetEntity="App\Entity\Product")
     * @ORM\JoinColumn(nullable=true)
     */
    protected $nextRenewalProduct;

    /**
     * @var ProductGroupInterface|null
     * @ORM\ManyToOne(targetEntity="Laertejjunior\SubscriptionBundle\Model\ProductGroupInterface", inversedBy="products")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    protected $group;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    protected $amount;

    /**
     * @var integer
     * @ORM\Column(type="integer", nullable=true, options={"unsigned":true})
     */
    protected $duration;

    /**
     * @var integer|null
     * @ORM\Column(type="integer", nullable=true, options={"unsigned":true})
     */
    protected $quota;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default": false})
     */
    protected $autoRenewal = false;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", name="is_default", options={"default": false})
     */
    protected $default = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $expirationDate;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $strategy;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default": false})
     */
    protected $enabled = false;

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        $this->subscriptions = new ArrayCollection();
        $this->features = new ArrayCollection();
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
     * @return mixed
     */
    public function getNextRenewalProduct()
    {
        return $this->nextRenewalProduct;
    }

    /**
     * @param mixed $nextRenewalProduct
     */
    public function setNextRenewalProduct($nextRenewalProduct)
    {
        $this->nextRenewalProduct = $nextRenewalProduct;
    }

    /**
     * @inheritDoc
     */
    public function getGroup(): ?ProductGroupInterface
    {
        return $this->group;
    }

    /**
     * @param ProductGroupInterface|null $group
     */
    public function setGroup(?ProductGroupInterface $group): self
    {
        $this->group = $group;

        return $this;
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
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getAmountInteger()
    {
        return intval($this->getAmount() * 100);
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @return integer
     */
    public function getDays()
    {
        return $this->getDuration() / (60 * 60 * 24);
    }

    /**
     * @param integer $duration
     */
    public function setDuration($duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuota()
    {
        return $this->quota;
    }

    /**
     * @param int|null $quota
     */
    public function setQuota($quota): self
    {
        $this->quota = $quota;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAutoRenewal()
    {
        return $this->autoRenewal;
    }

    /**
     * @param bool $autoRenewal
     */
    public function setAutoRenewal($autoRenewal): self
    {
        $this->autoRenewal = $autoRenewal;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isDefault()
    {
        return $this->default;
    }

    /**
     * @param bool $default
     */
    public function setDefault($default): self
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * @param \DateTime $expirationDate
     */
    public function setExpirationDate($expirationDate): self
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getStrategy()
    {
        return $this->strategy;
    }

    /**
     * @param string $strategy
     */
    public function setStrategy($strategy): self
    {
        $this->strategy = $strategy;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled($enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return FeatureInterface[]|ArrayCollection|Collection|PersistentCollection
     */
    public function getFeatures(): Collection
    {
        return $this->features;
    }

    /**
     * @return ArrayCollection|Collection|PersistentCollection|SubscriptionInterface[]
     */
    public function getSubscriptions(): Collection
    {
        return $this->subscriptions;
    }

    /**
     * @return ArrayCollection|Collection|PersistentCollection|AddonInterface[]
     */
    public function getAddons(): Collection
    {
        return $this->addons;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName() ?: 'No name';
    }
}
