<?php

namespace Shapecode\SubscriptionBundle\DependencyInjection;

use Shapecode\SubscriptionBundle\Entity\Addon;
use Shapecode\SubscriptionBundle\Entity\Feature;
use Shapecode\SubscriptionBundle\Entity\Product;
use Shapecode\SubscriptionBundle\Entity\ProductGroup;
use Shapecode\SubscriptionBundle\Entity\Subscription;
use Shapecode\SubscriptionBundle\Model\AddonInterface;
use Shapecode\SubscriptionBundle\Model\FeatureInterface;
use Shapecode\SubscriptionBundle\Model\ProductGroupInterface;
use Shapecode\SubscriptionBundle\Model\ProductInterface;
use Shapecode\SubscriptionBundle\Model\SubscriptionInterface;
use Shapecode\SubscriptionBundle\Strategy\ProductStrategyInterface;
use Shapecode\SubscriptionBundle\Strategy\SubscriptionStrategyInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * Class ShapecodeSubscriptionExtension
 *
 * @package Shapecode\SubscriptionBundle\DependencyInjection
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
