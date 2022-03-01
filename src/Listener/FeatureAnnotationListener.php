<?php

namespace Laertejjunior\SubscriptionBundle\Listener;

use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use Laertejjunior\SubscriptionBundle\Annotation\Feature;
use Laertejjunior\SubscriptionBundle\Event\FeatureAccessDeniedEvent;
use Laertejjunior\SubscriptionBundle\Feature\FeatureChecker;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class FeatureAnnotationListener
 *
 * @package Laertejjunior\SubscriptionBundle\Listener
 * @author  Nikita Loges
 */
class FeatureAnnotationListener implements EventSubscriberInterface
{
    
    /** @var Reader */
    protected $annotationReader;

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var FeatureChecker */
    protected $featureChecker;

    /**
     * @param Reader                   $annotationReader
     * @param EventDispatcherInterface $eventDispatcher
     * @param FeatureChecker           $featureChecker
     */
    public function __construct(
        Reader $annotationReader,
        EventDispatcherInterface $eventDispatcher,
        FeatureChecker $featureChecker
    ) {
        $this->annotationReader = $annotationReader;
        $this->eventDispatcher = $eventDispatcher;
        $this->featureChecker = $featureChecker;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    /**
     * @param ControllerEvent $event
     */
    public function onKernelController(ControllerEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $controllers = $event->getController();
        if (!is_array($controllers)) {
            return;
        }

        $this->handleAnnotation($event, $controllers);
    }

    /**
     * @param iterable $controllers
     */
    private function handleAnnotation(ControllerEvent $event, iterable $controllers): void
    {
        list($controller, $method) = $controllers;

        try {
            $controller = new ReflectionClass($controller);
        } catch (ReflectionException $e) {
            throw new RuntimeException('Failed to read annotation!');
        }

        $this->handleClassAnnotation($event, $controller);
        $this->handleMethodAnnotation($event, $controller, $method);
    }

    /**
     * @param ControllerEvent $event
     * @param ReflectionClass       $controller
     */
    private function handleClassAnnotation(ControllerEvent $event, ReflectionClass $controller): void
    {
        $annotation = $this->annotationReader->getClassAnnotation($controller, Feature::class);

        if ($annotation instanceof Feature) {
            $this->handleAnnotationObject($annotation, $event, $controller);
        }
    }

    /**
     * @param ControllerEvent $event
     * @param ReflectionClass       $controller
     * @param string                $method
     */
    private function handleMethodAnnotation(ControllerEvent $event, ReflectionClass $controller, string $method): void
    {
        $method = $controller->getMethod($method);
        $annotation = $this->annotationReader->getMethodAnnotation($method, Feature::class);

        if ($annotation instanceof Feature) {
            $this->handleAnnotationObject($annotation, $event, $controller, $method);
        }
    }

    /**
     * @param Feature               $annotation
     * @param ControllerEvent $event
     * @param ReflectionClass       $controller
     * @param string|null           $method
     */
    protected function handleAnnotationObject(Feature $annotation, ControllerEvent $event, ReflectionClass $controller, ?string $method = null): void
    {
        $features = $annotation->getFeatures();

        foreach ($features as $feature) {
            if (!$this->featureChecker->granted($feature)) {
                $this->eventDispatcher->dispatch(new FeatureAccessDeniedEvent($event, $feature));
//                throw new AccessDeniedHttpException();
            }
        }
    }
}
