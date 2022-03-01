<?php

namespace Laertejjunior\SubscriptionBundle\Model;

/**
 * Interface ProductGroupInterface
 *
 * @package Laertejjunior\SubscriptionBundle\Model
 * @author  Nikita Loges
 */
interface ProductGroupInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return ProductInterface[]
     */
    public function getProducts();
}
