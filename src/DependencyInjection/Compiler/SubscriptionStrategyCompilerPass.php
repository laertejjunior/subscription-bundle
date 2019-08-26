<?php

namespace Shapecode\SubscriptionBundle\DependencyInjection\Compiler;

use Shapecode\SubscriptionBundle\Subscription\ProductRegistry;
use Shapecode\SubscriptionBundle\Subscription\SubscriptionRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SubscriptionStrategyCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->productStrategies($container);
        $this->subscriptionStrategies($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function productStrategies(ContainerBuilder $container): void
    {
        $factory = $container->findDefinition(ProductRegistry::class);
        $tags = array_keys($container->findTaggedServiceIds('shapecode.product.strategy'));

        foreach ($tags as $id) {
            $factory->addMethodCall('addStrategy', [new Reference($id)]);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function subscriptionStrategies(ContainerBuilder $container): void
    {
        $factory = $container->findDefinition(SubscriptionRegistry::class);
        $tags = array_keys($container->findTaggedServiceIds('shapecode.subscription.strategy'));

        foreach ($tags as $id) {
            $factory->addMethodCall('addStrategy', [new Reference($id)]);
        }
    }
}
