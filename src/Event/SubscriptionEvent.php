<?php

namespace Shapecode\SubscriptionBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Shapecode\SubscriptionBundle\Model\SubscriptionInterface;

class SubscriptionEvent extends Event
{

    /**
     * Activate subscription.
     *
     * Triggered when a subscription is activated.
     */
    public const ACTIVATE_SUBSCRIPTION = 'shapecode.subscription.activate';

    /**
     * Renew a subscription.
     *
     * Triggered when subscription is renewed.
     */
    public const RENEW_SUBSCRIPTION = 'shapecode.subscription.renew';

    /**
     * Expire subscription.
     *
     * Triggered when subscription is expired.
     */
    public const EXPIRE_SUBSCRIPTION = 'shapecode.subscription.expire';

    /**
     * Disable subscription.
     *
     * Triggered when on-demand subscription is disabled.
     */
    public const DISABLE_SUBSCRIPTION = 'shapecode.subscription.disable';

    /**
     * @var SubscriptionInterface
     */
    private $subscription;

    /**
     * @var bool
     */
    private $fromRenew;

    /**
     * Constructor.
     *
     * @param SubscriptionInterface $subscription
     * @param boolean               $fromRenew
     */
    public function __construct(SubscriptionInterface $subscription, $fromRenew = false)
    {
        $this->subscription = $subscription;
        $this->fromRenew    = $fromRenew;
    }

    /**
     * @return SubscriptionInterface
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * @return bool
     */
    public function isFromRenew()
    {
        return $this->fromRenew;
    }
}
