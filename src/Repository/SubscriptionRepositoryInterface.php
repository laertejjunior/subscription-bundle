<?php

namespace Shapecode\SubscriptionBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Shapecode\SubscriptionBundle\Model\ProductGroupInterface;
use Shapecode\SubscriptionBundle\Model\ProductInterface;
use Shapecode\SubscriptionBundle\Model\SubscriptionInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface SubscriptionRepositoryInterface extends ObjectRepository
{
    /**
     * Get number of subscriptions with associated product without regard to the state.
     *
     * @param ProductInterface $product
     *
     * @return integer
     */
    public function getNumberOfSubscriptionsByProducts(ProductInterface $product);

    /**
     * Find subscriptions by product and state.
     *
     * @param ProductInterface $product
     * @param UserInterface    $user
     * @param boolean          $active
     *
     * @return SubscriptionInterface[]
     */
    public function findByProduct(ProductInterface $product, UserInterface $user, $active = true);

    /**
     * Find subscriptions by product group and state.
     *
     * @param ProductGroupInterface $group
     * @param UserInterface         $user
     * @param boolean               $active
     *
     * @return SubscriptionInterface[]
     */
    public function findByProductGroup(ProductGroupInterface $group, UserInterface $user, $active = true);
}
