<?php

namespace Laertejjunior\SubscriptionBundle\Model;

use App\Entity\ProductAddon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface SubscriptionInterface
 *
 * @package Laertejjunior\SubscriptionBundle\Model
 * @author  Nikita Loges
 */
interface SubscriptionInterface
{
    /**
     * @return UserInterface
     */
    public function getUser(): ?UserInterface;

    /**
     * @param UserInterface $user
     */
    public function setUser(?UserInterface $user): self;

    /**
     * @return \DateTimeInterface
     */
    public function getStartDate(): ?\DateTimeInterface;

    /**
     * @param \DateTimeInterface $dateTime
     */
    public function setStartDate(?\DateTimeInterface $dateTime): self;

    /**
     * @return \DateTimeInterface|null
     */
    public function getEndDate(): ?\DateTimeInterface;

    /**
     * @param null|\DateTimeInterface $dateTime
     */
    public function setEndDate(?\DateTimeInterface $dateTime): self;

    /**
     * @return AddonInterface[]|ArrayCollection|Collection|PersistentCollection
     */
    public function getAddons(): Collection;

    /**
     * @return ProductInterface
     */
    public function getProduct(): ?ProductInterface;

    /**
     * @param ProductInterface $product
     *
     * @return mixed
     */
    public function setProduct(?ProductInterface $product): self;

    /**
     * @return boolean
     */
    public function isActive(): bool;

    /**
     */
    public function activate(): void;

    /**
     */
    public function deactivate(): void;

    /**
     * @param boolean $renewal
     */
    public function setAutoRenewal(bool $renewal): self;

    /**
     * @return boolean
     */
    public function isAutoRenewal(): bool;

    /**
     * @param string $reason
     */
    public function setReason(?string $reason): self;

    /**
     * @return string
     */
    public function getReason(): ?string;

    /**
     * @param string $name
     */
    public function setStrategy(?string $name): self;

    /**
     * @return string
     */
    public function getStrategy(): ?string;
}
