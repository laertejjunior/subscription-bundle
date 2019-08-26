<?php

namespace Shapecode\SubscriptionBundle\Event;

use Shapecode\SubscriptionBundle\Model\SubscriptionInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class AbstractEvent
 *
 * @package Shapecode\SubscriptionBundle\Event
 * @author  Nikita Loges
 */
class AbstractEvent extends Event
{

    /** @var SubscriptionInterface */
    private $subscription;

    /** @var bool */
    private $fromRenew;

    /**
     * @param SubscriptionInterface $subscription
     * @param boolean               $fromRenew
     */
    public function __construct(SubscriptionInterface $subscription, $fromRenew = false)
    {
        $this->subscription = $subscription;
        $this->fromRenew = $fromRenew;
    }

    /**
     * @return SubscriptionInterface
     */
    public function getSubscription(): SubscriptionInterface
    {
        return $this->subscription;
    }

    /**
     * @return bool
     */
    public function isFromRenew(): bool
    {
        return $this->fromRenew;
    }
}
