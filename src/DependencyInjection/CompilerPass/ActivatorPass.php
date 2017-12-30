<?php

namespace Flagception\Bundle\FlagceptionBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ActivatorPass
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\DependencyInjection\CompilerPass
 */
class ActivatorPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $bag = $container->getDefinition('flagception.activator.chain_activator');

        $collection = [];
        foreach ($container->findTaggedServiceIds('flagception.activator') as $id => $tags) {
            foreach ($tags as $attributes) {
                $collection[] = [
                    'service' => new Reference($id),
                    'priority' => isset($attributes['priority']) ? $attributes['priority'] : 0
                ];
            }
        }

        // Sorting services
        usort($collection, function ($a, $b) {
            return ($b['priority'] < $a['priority']) ? -1 : (($b['priority'] > $a['priority']) ? 1 : 0);
        });

        // At least ... add ordered list
        foreach ($collection as $item) {
            $bag->addMethodCall('add', [$item['service']]);
        }
    }
}
