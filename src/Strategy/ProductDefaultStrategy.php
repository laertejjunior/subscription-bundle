<?php

namespace Shapecode\SubscriptionBundle\Strategy;

use Shapecode\SubscriptionBundle\Exception\ProductDefaultNotFoundException;
use Shapecode\SubscriptionBundle\Exception\ProductExpiredException;
use Shapecode\SubscriptionBundle\Exception\ProductIntegrityException;
use Shapecode\SubscriptionBundle\Exception\ProductQuoteExceededException;
use Shapecode\SubscriptionBundle\Model\ProductInterface;

/**
 * Class ProductDefaultStrategy
 *
 * @package Shapecode\SubscriptionBundle\Strategy
 * @author  Nikita Loges
 */
class ProductDefaultStrategy extends AbstractProductStrategy
{
    /**
     * {@inheritdoc}
     */
    public function getFinalProduct(ProductInterface $product): ProductInterface
    {
        try {

            $this->checkProductIntegrity($product);
            $this->checkExpiration($product);
            $this->checkQuote($product);

            return $product;

        } catch (ProductIntegrityException $exception) {
//
//            $this->getLogger()->error('Product integrity: {message}', [
//                'message' => $exception->getMessage(),
//            ]);

        } catch (ProductExpiredException $exception) {
//
//            $this->getLogger()->error('Product is expired: {message}', [
//                'message' => $exception->getMessage(),
//            ]);

        } catch (ProductQuoteExceededException $exception) {
//
//            $this->getLogger()->error('Product quota is exceeded: {message}', [
//                'message' => $exception->getMessage(),
//            ]);

        }

        return $this->getDefaultProduct();
    }

    /**
     * Get default product in case of that current product is not valid.
     *
     * @return ProductInterface
     *
     * @throws ProductDefaultNotFoundException
     */
    private function getDefaultProduct(): ProductInterface
    {
        $defaultProduct = $this->getProductRepository()->findDefault();

        if (null !== $defaultProduct) {
            return $defaultProduct;
        }

        throw new ProductDefaultNotFoundException('Default product was not found into the product repository');
    }

    /**
     * @inheritDoc
     */
    public function getShortName(): string
    {
        return 'default';
    }
}
