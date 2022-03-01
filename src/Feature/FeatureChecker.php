<?php

namespace Laertejjunior\SubscriptionBundle\Feature;

use Laertejjunior\SubscriptionBundle\Model\SubscriptionInterface;
use Laertejjunior\SubscriptionBundle\Subscription\FeatureManager;
use Laertejjunior\SubscriptionBundle\Subscription\SubscriptionConfig;
use Symfony\Component\Security\Core\Security;

/**
 * Class FeatureChecker
 *
 * @package Laertejjunior\SubscriptionBundle\Feature
 * @author  Nikita Loges
 */
class FeatureChecker
{

    /** @var SubscriptionConfig */
    protected $config;

    /** @var FeatureManager */
    protected $manager;

    /** @var Security */
    protected $security;

    /**
     * @param SubscriptionConfig $config
     * @param FeatureManager     $manager
     * @param Security           $security
     */
    public function __construct(
        SubscriptionConfig $config,
        FeatureManager $manager,
        Security $security
    ) {
        $this->config = $config;
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
        if ($this->config->getFeatureCheckOverride() !== null) {
            return $this->config->getFeatureCheckOverride();
        }

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
