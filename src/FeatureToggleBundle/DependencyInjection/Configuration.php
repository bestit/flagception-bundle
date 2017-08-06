<?php

namespace BestIt\FeatureToggleBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('best_it_feature_toggle');

        $rootNode
            ->children()
                ->booleanNode('use_annotation')->defaultFalse()->end()
                ->arrayNode('features')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->booleanNode('active')->defaultFalse()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('cookie_stash')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('active')->defaultFalse()->end()
                        ->scalarNode('name')->defaultValue('best_it_feature_toggle')->end()
                    ->end()
                ->end()
                ->arrayNode('annotation')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('active')->defaultFalse()->end()
                    ->end()
                ->end()
                ->arrayNode('routing_metadata')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('active')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
