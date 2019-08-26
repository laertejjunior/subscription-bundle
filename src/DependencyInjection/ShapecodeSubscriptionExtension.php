<?php

namespace Shapecode\SubscriptionBundle\DependencyInjection;

use Shapecode\SubscriptionBundle\Strategy\ProductStrategyInterface;
use Shapecode\SubscriptionBundle\Strategy\SubscriptionStrategyInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * Class ShapecodeSubscriptionExtension
 *
 * @package Shapecode\SubscriptionBundle\DependencyInjection
 * @author  Nikita Loges
 */
class ShapecodeSubscriptionExtension extends ConfigurableExtension
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
}
