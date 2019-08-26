<?php

namespace Shapecode\SubscriptionBundle\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Shapecode\SubscriptionBundle\Model\ProductInterface;
use Shapecode\SubscriptionBundle\Model\SubscriptionInterface;

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
     * Find subscription by his ID.
     *
     * @param int|string $id
     *
     * @return SubscriptionInterface|null
     */
    public function findById($id);
}
