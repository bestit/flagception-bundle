<?php

namespace Flagception\Bundle\FlagceptionBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ExpressionProviderPass
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\DependencyInjection\CompilerPass
 */
class ExpressionProviderPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $factory = $container->getDefinition('flagception.factory.expression_language_factory');

        foreach ($container->findTaggedServiceIds('flagception.expression_language_provider') as $id => $tags) {
            foreach ($tags as $attributes) {
                $factory->addMethodCall('addProvider', [new Reference($id)]);
            }
        }
    }
}
