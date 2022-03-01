<?php

namespace Laertejjunior\SubscriptionBundle\Event;

use Laertejjunior\SubscriptionBundle\Model\SubscriptionInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class AbstractEvent
 *
 * @package Laertejjunior\SubscriptionBundle\Event
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
