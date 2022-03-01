<?php

namespace Laertejjunior\SubscriptionBundle\DependencyInjection;

use Laertejjunior\SubscriptionBundle\Entity\Addon;
use Laertejjunior\SubscriptionBundle\Entity\Feature;
use Laertejjunior\SubscriptionBundle\Entity\Product;
use Laertejjunior\SubscriptionBundle\Entity\ProductGroup;
use Laertejjunior\SubscriptionBundle\Entity\Subscription;
use Laertejjunior\SubscriptionBundle\Model\AddonInterface;
use Laertejjunior\SubscriptionBundle\Model\FeatureInterface;
use Laertejjunior\SubscriptionBundle\Model\ProductGroupInterface;
use Laertejjunior\SubscriptionBundle\Model\ProductInterface;
use Laertejjunior\SubscriptionBundle\Model\SubscriptionInterface;
use Laertejjunior\SubscriptionBundle\Strategy\ProductStrategyInterface;
use Laertejjunior\SubscriptionBundle\Strategy\SubscriptionStrategyInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * Class ShapecodeSubscriptionExtension
 *
 * @package Laertejjunior\SubscriptionBundle\DependencyInjection
 * @author  Nikita Loges
 */
class ShapecodeSubscriptionExtension extends ConfigurableExtension implements PrependExtensionInterface
{

    /**
     * {@inheritdoc}
     */
    public function loadInternal(array $configs, ContainerBuilder $container)
    {
        $container->registerForAutoconfiguration(ProductStrategyInterface::class)->addTag('shapecode.product.strategy');
        $container->registerForAutoconfiguration(SubscriptionStrategyInterface::class)->addTag('shapecode.subscription.strategy');

        $container->setParameter('shapecode_subscription.config', $configs);

        $locator = new FileLocator(__DIR__.'/../Resources/config');
        $loader = new Loader\YamlFileLoader($container, $locator);
        $loader->load('services.yml');
    }

    /**
     * @inheritDoc
     */
    public function prepend(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('doctrine', [
            'orm' => [
                'resolve_target_entities' => [
                    AddonInterface::class        => Addon::class,
                    FeatureInterface::class      => Feature::class,
                    ProductInterface::class      => Product::class,
                    ProductGroupInterface::class => ProductGroup::class,
                    SubscriptionInterface::class => Subscription::class,
                ],
            ],
        ]);
    }
}
