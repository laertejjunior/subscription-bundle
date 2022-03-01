<?php

namespace Laertejjunior\SubscriptionBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * Class FeatureAccessDeniedEvent
 *
 * @package Laertejjunior\SubscriptionBundle\Event
 * @author  Nikita Loges
 */
class FeatureAccessDeniedEvent extends Event
{

    /** @var FilterControllerEvent */
    protected $event;

    /** @var string */
    protected $feature;

    /**
     * @param FilterControllerEvent $event
     * @param string                $feature
     */
    public function __construct(FilterControllerEvent $event, string $feature)
    {
        $this->event = $event;
        $this->feature = $feature;
    }

    /**
     * @return FilterControllerEvent
     */
    public function getEvent(): FilterControllerEvent
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
