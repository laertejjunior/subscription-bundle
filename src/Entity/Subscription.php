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
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Subscription
 *
 * @package App\Entity
 * @author  Nikita Loges
 *
 * @ORM\Entity(repositoryClass="App\Repository\SubscriptionRepository")
 */
class Subscription implements SubscriptionInterface
{

    /**
     * @var int
     * @ORM\Column(type="bigint", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var UserInterface
     * @ORM\ManyToOne(targetEntity="Symfony\Component\Security\Core\User\UserInterface", inversedBy="subscriptions")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $user;

    /**
     * @var ProductInterface
     * @ORM\ManyToOne(targetEntity="Shapecode\SubscriptionBundle\Model\ProductInterface", inversedBy="subscriptions")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $product;

    /**
     * @var ArrayCollection|PersistentCollection|Collection|AddonInterface[]
     * @ORM\ManyToMany(targetEntity="Shapecode\SubscriptionBundle\Model\AddonInterface", inversedBy="subscriptions")
     */
    protected $addons;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $startDate;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $endDate;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false, options={"default": false})
     */
    protected $active = false;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", nullable=false, options={"default": false})
     */
    protected $autoRenewal = false;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $reason;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $strategy;

    /**
     *
     */
    public function __construct()
    {
        $this->setStartDate(new \DateTime());
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
     * @return UserInterface
     */
    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    /**
     * @param UserInterface $user
     */
    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }

    /**
     * @return ProductInterface
     */
    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    /**
     * @param ProductInterface $product
     */
    public function setProduct(?ProductInterface $product): void
    {
        $this->product = $product;
    }

    /**
     * @return AddonInterface[]|ArrayCollection|Collection|PersistentCollection
     */
    public function getAddons(): Collection
    {
        return $this->addons;
    }

    /**
     * {@inheritdoc}
     */
    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    /**
     * {@inheritdoc}
     */
    public function setStartDate(?\DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * {@inheritdoc}
     */
    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    /**
     * {@inheritdoc}
     */
    public function setEndDate(?\DateTime $finishDate): void
    {
        $this->endDate = $finishDate;
    }

    /**
     * {@inheritdoc}
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param boolean $active
     */
    public function setActive(bool $active)
    {
        $this->active = $active;
    }

    /**
     * {@inheritdoc}
     */
    public function activate(): void
    {
        $this->setActive(true);
    }

    /**
     * {@inheritdoc}
     */
    public function deactivate(): void
    {
        $this->setActive(false);
    }

    /**
     * @return bool
     */
    public function isAutoRenewal(): bool
    {
        return $this->autoRenewal;
    }

    /**
     * @param bool $autoRenewal
     */
    public function setAutoRenewal(bool $autoRenewal): void
    {
        $this->autoRenewal = $autoRenewal;
    }

    /**
     * @return string
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * @param string $reason
     */
    public function setReason(?string $reason): void
    {
        $this->reason = $reason;
    }

    /**
     * @param string $name
     */
    public function setStrategy(?string $name): void
    {
        $this->strategy = $name;
    }

    /**
     * @return string
     */
    public function getStrategy(): ?string
    {
        return $this->strategy;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf(
            '%s - %s',
            $this->getStartDate() ? $this->getStartDate()->format('Y-m-d H:i') : '??',
            $this->getEndDate() ? $this->getEndDate()->format('Y-m-d H:i') : '??'
        );
    }
}
