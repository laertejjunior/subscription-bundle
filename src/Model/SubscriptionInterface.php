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
     * @return \DateTimeImmutable
     */
    public function getStartDate(): ?\DateTimeImmutable;

    /**
     * @param \DateTimeImmutable $dateTime
     */
    public function setStartDate(?\DateTimeImmutable $dateTime): self;

    /**
     * @return \DateTimeImmutable|null
     */
    public function getEndDate(): ?\DateTimeImmutable;

    /**
     * @param null|\DateTimeImmutable $dateTime
     */
    public function setEndDate(?\DateTimeImmutable $dateTime): self;

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
    public function setAutoRenewal(bool $renewal): void;

    /**
     * @return boolean
     */
    public function isAutoRenewal(): bool;

    /**
     * @param string $reason
     */
    public function setReason(?string $reason): void;

    /**
     * @return string
     */
    public function getReason(): ?string;

    /**
     * @param string $name
     */
    public function setStrategy(?string $name): void;

    /**
     * @return string
     */
    public function getStrategy(): ?string;
}
