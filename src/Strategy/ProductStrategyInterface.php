<?php

namespace Laertejjunior\SubscriptionBundle\Strategy;

use Laertejjunior\SubscriptionBundle\Exception\ProductDefaultNotFoundException;
use Laertejjunior\SubscriptionBundle\Model\ProductInterface;

interface ProductStrategyInterface
{
    /**
     * Get final product.
     *
     * Determine the final based on your own algorithms.
     *
     * @param ProductInterface $product
     *
     * @return ProductInterface
     *
     * @throws ProductDefaultNotFoundException
     */
    public function getFinalProduct(ProductInterface $product): ProductInterface;

    /**
     * @return string
     */
    public function getShortName(): string;
}
