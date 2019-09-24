<?php

namespace Shapecode\SubscriptionBundle\Feature;

use Shapecode\SubscriptionBundle\Model\SubscriptionInterface;
use Shapecode\SubscriptionBundle\Subscription\FeatureManager;
use Symfony\Component\Security\Core\Security;

/**
 * Class FeatureChecker
 *
 * @package Shapecode\SubscriptionBundle\Feature
 * @author  Nikita Loges
 */
class FeatureChecker
{

    /** @var FeatureManager */
    protected $manager;

    /** @var Security */
    protected $security;

    /**
     * @param FeatureManager $manager
     * @param Security       $security
     */
    public function __construct(FeatureManager $manager, Security $security)
    {
        $this->manager = $manager;
        $this->security = $security;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function granted(string $key): bool
    {
        $user = $this->security->getUser();

        return $this->manager->userHasFeature($user, $key);
    }

    /**
     * @return bool
     */
    public function hasActiveSubscription(): bool
    {
        $user = $this->security->getUser();

        return $this->manager->userHasActiveSubscription($user);
    }

    /**
     * @return SubscriptionInterface|null
     */
    public function getActiveSubscription(): ?SubscriptionInterface
    {
        $user = $this->security->getUser();

        return $this->manager->userGetActiveSubscription($user);
    }
}
