<?php

namespace Shapecode\SubscriptionBundle\Strategy;

use Shapecode\SubscriptionBundle\Exception\PermanentSubscriptionException;
use Shapecode\SubscriptionBundle\Model\ProductInterface;
use Shapecode\SubscriptionBundle\Model\SubscriptionInterface;

/**
 * Class SubscriptionEndLastStrategy
 *
 * @package Shapecode\SubscriptionBundle\Strategy
 * @author  Nikita Loges
 */
class SubscriptionEndLastStrategy extends AbstractSubscriptionStrategy
{
    /**
     * @inheritdoc
     */
    public function createSubscription(ProductInterface $product, array $subscriptions = []): SubscriptionInterface
    {
        if (empty($subscriptions)) {
            return $this->create($this->createCurrentDate(), $product);
        }

        $startDate = null;
        foreach ($subscriptions as $subscription) {

            // Subscription is permanent, don't continue
            if (null === $subscription->getEndDate()) {
                $startDate = null;
                break;
            }

            // Catch the subscription with higher end date
            if (null === $startDate || $startDate < $subscription->getEndDate()) {
                $startDate = $subscription->getEndDate();
            }
        }

        // It's a permanent subscription
        if (null === $startDate) {

            if (count($subscriptions) > 1) {
                throw new PermanentSubscriptionException(
                    'More than one subscription per product is not allowed when there is a permanent subscription 
                    enabled. Maybe you are mixing different strategies?'
                );
            }

            return $subscriptions[0];
        }

        // Check if subscription is expired
        if ($startDate !== null && time() > $startDate->getTimestamp()) {
            $startDate = $this->createCurrentDate();
        }

        // Date should use the \DateTimeImmutable (a little fix)
        if (!$startDate instanceof \DateTime) {
            $startDate = (new \DateTime())->setTimestamp($startDate->getTimestamp());
        }

        return $this->create($startDate, $product);
    }

    /**
     * Create subscription.
     *
     * @param \DateTime $startDate
     * @param ProductInterface   $product
     *
     * @return SubscriptionInterface
     */
    protected function create(\DateTime $startDate, ProductInterface $product): SubscriptionInterface
    {
        $endDate = null;

        if ($product->getDuration() > 0) {
            $endDate = clone $startDate;
            $endDate->modify(sprintf('+%s seconds', $product->getDuration()));
        }

        // Create the new subscription
        $subscription = $this->createSubscriptionInstance();
        $subscription->setProduct($product);
        $subscription->setStartDate($startDate);
        $subscription->setEndDate($endDate);
        $subscription->setAutoRenewal($product->isAutoRenewal());

        return $subscription;
    }

    /**
     * @inheritDoc
     */
    public function getShortName(): string
    {
        return 'end_last';
    }
}
