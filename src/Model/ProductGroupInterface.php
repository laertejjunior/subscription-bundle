<?php

namespace Shapecode\SubscriptionBundle\Model;

/**
 * Interface ProductGroupInterface
 *
 * @package Shapecode\SubscriptionBundle\Model
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
