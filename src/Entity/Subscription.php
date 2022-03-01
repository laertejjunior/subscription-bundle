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
     * @ORM\ManyToOne(targetEntity="Laertejjunior\SubscriptionBundle\Model\ProductInterface", inversedBy="subscriptions")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    protected $product;

    /**
     * @var ArrayCollection|PersistentCollection|Collection|AddonInterface[]
     * @ORM\ManyToMany(targetEntity="Laertejjunior\SubscriptionBundle\Model\AddonInterface", inversedBy="subscriptions")
     */
    protected $addons;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime")
     */
    protected $startDate;

    /**
     * @var \DateTimeImmutable
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
        $this->setStartDate(new \DateTimeImmutable());
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
    public function setUser(?UserInterface $user): self
    {
        $this->user = $user;

        return $this;
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
    public function setProduct(?ProductInterface $product): self
    {
        $this->product = $product;

        return $this;
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
    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    /**
     * {@inheritdoc}
     */
    public function setStartDate(?\DateTimeImmutable $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    /**
     * {@inheritdoc}
     */
    public function setEndDate(?\DateTimeImmutable $finishDate): self
    {
        $this->endDate = $finishDate;

        return $this;
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

        return $this;
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
    public function setAutoRenewal(bool $autoRenewal): self
    {
        $this->autoRenewal = $autoRenewal;

        return $this;
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
    public function setReason(?string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * @param string $name
     */
    public function setStrategy(?string $name): self
    {
        $this->strategy = $name;

        return $this;
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
