<?php

namespace Shapecode\SubscriptionBundle\Strategy;

use Shapecode\SubscriptionBundle\Exception\ProductExpiredException;
use Shapecode\SubscriptionBundle\Exception\ProductIntegrityException;
use Shapecode\SubscriptionBundle\Exception\ProductQuoteExceededException;
use Shapecode\SubscriptionBundle\Model\ProductInterface;
use Shapecode\SubscriptionBundle\Repository\ProductRepositoryInterface;
use Shapecode\SubscriptionBundle\Repository\SubscriptionRepositoryInterface;
use Shapecode\SubscriptionBundle\Subscription\ProductRegistry;
use Shapecode\SubscriptionBundle\Subscription\SubscriptionConfig;
use Shapecode\SubscriptionBundle\Subscription\SubscriptionRegistry;

abstract class AbstractProductStrategy implements ProductStrategyInterface
{

    /** @var SubscriptionConfig */
    protected $config;

    /** @var SubscriptionRegistry */
    protected $subscriptionRegistry;

    /** @var ProductRegistry */
    protected $productRegistry;

    /**
     * @param SubscriptionConfig   $config
     * @param SubscriptionRegistry $subscriptionRegistry
     * @param ProductRegistry      $productRegistry
     */
    public function __construct(
        SubscriptionConfig $config,
        SubscriptionRegistry $subscriptionRegistry,
        ProductRegistry $productRegistry
    ) {
        $this->config = $config;
        $this->subscriptionRegistry = $subscriptionRegistry;
        $this->productRegistry = $productRegistry;
    }

    /**
     * @return ProductRepositoryInterface
     */
    protected function getProductRepository()
    {
        return $this->config->getProductRepository();
    }

    /**
     * @return SubscriptionRepositoryInterface
     */
    protected function getSubscriptionRepository()
    {
        return $this->config->getSubscriptionRepository();
    }

    /**
     * Check the product model integrity.
     *
     * @param ProductInterface $product
     *
     * @throws ProductIntegrityException
     */
    final public function checkProductIntegrity(ProductInterface $product): void
    {
        if ($product->isDefault() && null !== $product->getQuota()) {

            throw new ProductIntegrityException(sprintf(
                'The product "%s" is a default product with a quota (%s). Default products can not have a quote value.',
                $product->getName(),
                $product->getQuota()
            ));

        }

        if ($product->isDefault() && null !== $product->getExpirationDate()) {

            throw new ProductIntegrityException(sprintf(
                'The product "%s" is a default product with expiration date (%s). Default products can not have a expiration date.',
                $product->getName(),
                $product->getExpirationDate()->format('Y-m-d H:i:s')
            ));

        }
    }

    /**
     * Check product expiration.
     *
     * @param ProductInterface $product
     *
     * @throws ProductExpiredException
     */
    public function checkExpiration(ProductInterface $product): void
    {
        $expirationDate = $product->getExpirationDate();

        if (null === $expirationDate || new \DateTime() <= $expirationDate) {
            return;
        }

        throw new ProductExpiredException(sprintf(
            'The product "%s" has been expired at %s.',
            $product->getName(),
            $expirationDate->format('Y-m-d H:i:s')
        ));
    }

    /**
     * Check product quote.
     *
     * @param ProductInterface $product
     *
     * @throws ProductQuoteExceededException
     */
    public function checkQuote(ProductInterface $product): void
    {
        // Unlimited quote
        if (null === $product->getQuota()) {
            return;
        }

        // Calculate the current quote
        $currentQuote = $this->getSubscriptionRepository()->getNumberOfSubscriptionsByProducts($product);

        if ($currentQuote < $product->getQuota()) {
            return;
        }

        throw new ProductQuoteExceededException(sprintf(
            'The product "%s" quota is %s. This is exceeded. Increase the quota.',
            $product->getName(),
            $product->getQuota()
        ));
    }
}
