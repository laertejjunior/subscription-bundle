<?php

namespace Laertejjunior\SubscriptionBundle\Twig\Extension;

use Laertejjunior\SubscriptionBundle\Feature\FeatureChecker;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class FeatureExtension
 *
 * @package Laertejjunior\SubscriptionBundle\Twig\Extension
 * @author  Nikita Loges
 */
class FeatureExtension extends AbstractExtension
{

    /** @var FeatureChecker */
    protected $featureChecker;

    /**
     * @param FeatureChecker $featureChecker
     */
    public function __construct(FeatureChecker $featureChecker)
    {
        $this->featureChecker = $featureChecker;
    }

    /**
     * @inheritDoc
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('feature_granted', [$this->featureChecker, 'granted']),
            new TwigFunction('subscription_has_active', [$this->featureChecker, 'hasActiveSubscription']),
            new TwigFunction('subscription_active', [$this->featureChecker, 'getActiveSubscription']),
        ];
    }

}
