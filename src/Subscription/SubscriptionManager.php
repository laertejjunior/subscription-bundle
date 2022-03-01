<?php

namespace Laertejjunior\SubscriptionBundle\Subscription;

use Doctrine\Persistence\ManagerRegistry;
use Laertejjunior\SubscriptionBundle\Event\ActivateEvent;
use Laertejjunior\SubscriptionBundle\Event\DisableEvent;
use Laertejjunior\SubscriptionBundle\Event\ExpireEvent;
use Laertejjunior\SubscriptionBundle\Event\RenewEvent;
use Laertejjunior\SubscriptionBundle\Exception\PermanentSubscriptionException;
use Laertejjunior\SubscriptionBundle\Exception\ProductDefaultNotFoundException;
use Laertejjunior\SubscriptionBundle\Exception\StrategyNotFoundException;
use Laertejjunior\SubscriptionBundle\Exception\SubscriptionIntegrityException;
use Laertejjunior\SubscriptionBundle\Exception\SubscriptionRenewalException;
use Laertejjunior\SubscriptionBundle\Exception\SubscriptionStatusException;
use Laertejjunior\SubscriptionBundle\Model\ProductInterface;
use Laertejjunior\SubscriptionBundle\Model\SubscriptionInterface;
use Laertejjunior\SubscriptionBundle\Strategy\SubscriptionStrategyInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class SubscriptionManager
 *
 * @package Laertejjunior\SubscriptionBundle\Subscription
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
