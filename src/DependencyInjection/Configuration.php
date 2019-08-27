<?php

namespace Shapecode\SubscriptionBundle\DependencyInjection;

use Shapecode\SubscriptionBundle\Strategy\ProductDefaultStrategy;
use Shapecode\SubscriptionBundle\Strategy\SubscriptionEndLastStrategy;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package Shapecode\SubscriptionBundle\DependencyInjection
 * @author  Nikita Loges
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('shapecode_subscription');

        $rootNode
            ->children()
                ->scalarNode('subscription_class')
                    ->defaultValue('App/Entity/Subscription')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('product_class')
                    ->defaultValue('App/Entity/Product')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('product_group_class')
                    ->defaultValue('App/Entity/ProductGroup')
                    ->cannotBeEmpty()
                ->end()

                ->scalarNode('default_product_strategy')
                    ->defaultValue('default')
                    ->cannotBeEmpty()
                ->end()

                ->scalarNode('default_subscription_strategy')
                    ->defaultValue('end_last')
                    ->cannotBeEmpty()
                ->end()

                ->arrayNode('reasons')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('renew')
                            ->defaultValue('Subscription expired and auto-renewal')
                        ->end()
                        ->scalarNode('expire')
                            ->defaultValue('Subscription expired')
                        ->end()
                        ->scalarNode('disable')
                            ->defaultValue('Subscription disabled')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
