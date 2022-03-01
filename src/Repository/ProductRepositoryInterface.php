<?php

namespace Laertejjunior\SubscriptionBundle\Repository;

use Doctrine\Persistence\ObjectRepository;
use Laertejjunior\SubscriptionBundle\Model\ProductInterface;

interface ProductRepositoryInterface extends ObjectRepository
{
    /**
     * Find a default product.
     *
     * @return ProductInterface|null
     */
    public function findDefault();
}
