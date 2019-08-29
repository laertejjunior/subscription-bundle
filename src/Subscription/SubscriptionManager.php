<?php

namespace Shapecode\SubscriptionBundle\Subscription;

use Doctrine\Common\Persistence\ManagerRegistry;
use Shapecode\SubscriptionBundle\Event\ActivateEvent;
use Shapecode\SubscriptionBundle\Event\DisableEvent;
use Shapecode\SubscriptionBundle\Event\ExpireEvent;
use Shapecode\SubscriptionBundle\Event\RenewEvent;
use Shapecode\SubscriptionBundle\Exception\PermanentSubscriptionException;
use Shapecode\SubscriptionBundle\Exception\ProductDefaultNotFoundException;
use Shapecode\SubscriptionBundle\Exception\StrategyNotFoundException;
use Shapecode\SubscriptionBundle\Exception\SubscriptionIntegrityException;
use Shapecode\SubscriptionBundle\Exception\SubscriptionRenewalException;
use Shapecode\SubscriptionBundle\Exception\SubscriptionStatusException;
use Shapecode\SubscriptionBundle\Model\ProductInterface;
use Shapecode\SubscriptionBundle\Model\SubscriptionInterface;
use Shapecode\SubscriptionBundle\Strategy\SubscriptionStrategyInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class SubscriptionManager
 *
 * @package Shapecode\SubscriptionBundle\Subscription
 * @author  Nikita Loges
 */
class SubscriptionManager
{

    /** @var ManagerRegistry */
    protected $registry;

    /** @var SubscriptionRegistry */
    protected $subscriptionRegistry;

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var SubscriptionConfig */
    protected $config;

    /**
     * @param ManagerRegistry          $registry
     * @param SubscriptionRegistry     $subscriptionRegistry
     * @param EventDispatcherInterface $eventDispatcher
     * @param SubscriptionConfig       $config
     */
    public function __construct(
        ManagerRegistry $registry,
        SubscriptionRegistry $subscriptionRegistry,
        EventDispatcherInterface $eventDispatcher,
        SubscriptionConfig $config
    ) {
        $this->registry = $registry;
        $this->subscriptionRegistry = $subscriptionRegistry;
        $this->eventDispatcher = $eventDispatcher;
        $this->config = $config;
    }

    /**
     * Create a new subscription with a determinate strategy.
     *
     * @param ProductInterface $product
     * @param UserInterface    $user
     * @param null             $strategyName
     *
     * @return SubscriptionInterface
     *
     * @throws PermanentSubscriptionException
     * @throws StrategyNotFoundException
     * @throws SubscriptionIntegrityException
     */
    public function create(ProductInterface $product, UserInterface $user, $strategyName = null): SubscriptionInterface
    {
        // Get strategy
        $strategyName = $strategyName ?? $product->getStrategy();
        $strategyName = $strategyName ?? $this->config->getDefaultSubscriptionStrategy();

        $strategy = $this->subscriptionRegistry->get($strategyName);

        $repo = $this->config->getSubscriptionRepository();

        // Get current enabled subscriptions of product
        if ($product->getGroup()) {
            $subscriptions = $repo->findByProductGroup($product->getGroup(), $user);
        }

        if (empty($subscriptions)) {
            $subscriptions = $repo->findByProduct($product, $user);
        }

        $subscription = $strategy->createSubscription($product, $subscriptions);
        $subscription->setStrategy($strategyName);
        $subscription->setUser($user);

        return $subscription;
    }

    /**
     * Activate subscription.
     *
     * @param SubscriptionInterface $subscription
     * @param boolean               $isRenew
     *
     * @throws SubscriptionIntegrityException
     * @throws StrategyNotFoundException
     * @throws SubscriptionStatusException
     * @throws ProductDefaultNotFoundException
     */
    public function activate(SubscriptionInterface $subscription, $isRenew = false): void
    {
        $this->checkSubscriptionNonActive($subscription);

        $strategy = $this->getStrategyFromSubscription($subscription);
        $finalProduct = $strategy->getProductStrategy()->getFinalProduct($subscription->getProduct());

        $subscription->setProduct($finalProduct);
        $subscription->activate();

        $event = new ActivateEvent($subscription, $isRenew);
        $this->eventDispatcher->dispatch(ActivateEvent::class, $event);

        $this->save($subscription);
    }

    /**
     * Renew subscription that has been expired.
     *
     * @param SubscriptionInterface $subscription
     *
     * @return SubscriptionInterface
     *
     * @throws SubscriptionIntegrityException
     * @throws SubscriptionRenewalException
     * @throws ProductDefaultNotFoundException
     * @throws StrategyNotFoundException
     * @throws PermanentSubscriptionException
     * @throws SubscriptionStatusException
     */
    public function renew(SubscriptionInterface $subscription): SubscriptionInterface
    {
        $this->checkSubscriptionRenewable($subscription);
        $this->checkSubscriptionActive($subscription);

        // Expire the last subscription
        $this->expire($subscription, 'renew', true);

        // Get the next renewal product
        $renewalProduct = $this->getRenewalProduct($subscription->getProduct());
        $strategy = $this->getStrategyFromSubscription($subscription);
        $finalProduct = $strategy->getProductStrategy()->getFinalProduct($renewalProduct);

        // Create new subscription (following the way of expired subscription)
        $newSubscription = $this->create($finalProduct, $subscription->getUser(), $finalProduct->getStrategy());
        $newSubscription->setAutoRenewal(true);

        // Activate the next subscription
        $this->activate($newSubscription, true);

        $event = new RenewEvent($newSubscription);
        $this->eventDispatcher->dispatch(RenewEvent::class, $event);

        $this->save($subscription);

        return $newSubscription;
    }

    /**
     * Get next roll product.
     *
     * @param ProductInterface $product
     *
     * @return ProductInterface
     */
    protected function getRenewalProduct(ProductInterface $product): ProductInterface
    {
        if (null === $product->getNextRenewalProduct()) {
            return $product;
        }

        return $product->getNextRenewalProduct();
    }

    /**
     * Expire subscription.
     *
     * @param SubscriptionInterface $subscription
     * @param string                $reason The reason codename that you want set into the subscription
     * @param boolean               $isRenew
     */
    public function expire(SubscriptionInterface $subscription, $reason = 'expire', $isRenew = false): void
    {
        $subscription->setReason($this->config->getReason($reason));
        $subscription->deactivate();

        $event = new ExpireEvent($subscription, $isRenew);
        $this->eventDispatcher->dispatch(ExpireEvent::class, $event);

        $this->save($subscription);
    }

    /**
     * Disable subscription.
     *
     * @param SubscriptionInterface $subscription
     */
    public function disable(SubscriptionInterface $subscription): void
    {
        $subscription->setReason($this->config->getReason('disable'));
        $subscription->deactivate();

        $event = new DisableEvent($subscription);
        $this->eventDispatcher->dispatch(DisableEvent::class, $event);

        $this->save($subscription);
    }

    /**
     * Get strategy from subscription.
     *
     * @param SubscriptionInterface $subscription
     *
     * @return SubscriptionStrategyInterface
     *
     * @throws StrategyNotFoundException
     */
    protected function getStrategyFromSubscription(SubscriptionInterface $subscription): SubscriptionStrategyInterface
    {
        $strategyName = $subscription->getStrategy();

        return $this->subscriptionRegistry->get($strategyName);
    }

    /**
     * Check if subscription is auto-renewable.
     *
     * @param SubscriptionInterface $subscription
     *
     * @throws SubscriptionRenewalException
     */
    protected function checkSubscriptionRenewable(SubscriptionInterface $subscription): void
    {
        if (null === $subscription->getEndDate()) {
            throw new SubscriptionRenewalException('A permanent subscription can not be renewed.');
        }

        if (!$subscription->isAutoRenewal()) {
            throw new SubscriptionRenewalException('The current subscription is not auto-renewal.');
        }

        if (!$subscription->getProduct()->isAutoRenewal()) {
            throw new SubscriptionRenewalException(sprintf(
                'The product "%s" is not auto-renewal. Maybe is disabled?',
                $subscription->getProduct()->getName()
            ));
        }
    }

    /**
     * @param SubscriptionInterface $subscription
     *
     * @throws SubscriptionStatusException
     */
    protected function checkSubscriptionNonActive(SubscriptionInterface $subscription): void
    {
        if (!$subscription->isActive()) {
            return;
        }

        throw new SubscriptionStatusException('Subscription is active.');
    }

    /**
     * @param SubscriptionInterface $subscription
     *
     * @throws SubscriptionStatusException
     */
    protected function checkSubscriptionActive(SubscriptionInterface $subscription): void
    {
        if ($subscription->isActive()) {
            return;
        }

        throw new SubscriptionStatusException('Subscription is not active.');
    }

    /**
     * @param SubscriptionInterface $subscription
     */
    public function save(SubscriptionInterface $subscription)
    {
        $em = $this->registry->getManager();

        $em->persist($subscription);
        $em->flush();
    }
}
