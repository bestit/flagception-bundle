<?php

namespace BestIt\FeatureToggleBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class StashPass
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\DependencyInjection\CompilerPass
 */
class StashPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $bag = $container->getDefinition('best_it_feature_toggle.bag.stash_bag');

        $collection = [];
        foreach ($container->findTaggedServiceIds('best_it_feature_toggle.stash') as $id => $tags) {
            foreach ($tags as $attributes) {
                $collection[] = [
                    'service' => new Reference($id),
                    'priority' => $attributes['priority'] ?? 0
                ];
            }
        }

        // Sorting services
        usort($collection, function ($first, $second) {
            return $second['priority'] <=> $first['priority'];
        });

        // At least ... add ordered list
        foreach ($collection as $item) {
            $bag->addMethodCall('add', [$item['service']]);
        }
    }
}
