<?php

namespace Shapecode\SubscriptionBundle;

use Shapecode\SubscriptionBundle\DependencyInjection\Compiler\SubscriptionStrategyCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class ShapecodeSubscriptionBundle
 *
 * @package Shapecode\SubscriptionBundle
 * @author  Nikita Loges
 */
class ShapecodeSubscriptionBundle extends Bundle
{
    /**
     *{@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new SubscriptionStrategyCompilerPass());
    }
}
