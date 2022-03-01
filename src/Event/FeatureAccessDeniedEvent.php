<?php

namespace Laertejjunior\SubscriptionBundle\Event;

use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class FeatureAccessDeniedEvent
 *
 * @package Laertejjunior\SubscriptionBundle\Event
 * @author  Nikita Loges
 */
class FeatureAccessDeniedEvent extends Event
{

    /** @var ControllerEvent */
    protected $event;

    /** @var string */
    protected $feature;

    /**
     * @param ControllerEvent $event
     * @param string $feature
     */
    public function __construct(ControllerEvent $event, string $feature)
    {
        $this->event = $event;
        $this->feature = $feature;
    }

    /**
     * @return ControllerEvent
     */
    public function getEvent(): ControllerEvent
    {
        return $this->event;
    }

    /**
     * @return string
     */
    public function getFeature(): string
    {
        return $this->feature;
    }
}
