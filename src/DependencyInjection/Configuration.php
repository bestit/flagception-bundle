<?php

namespace Flagception\Bundle\FlagceptionBundle\DependencyInjection;

use Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator\ActivatorConfiguratorInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Configurators
     *
     * @var ActivatorConfiguratorInterface[]
     */
    private $configurators;

    /**
     * Configuration constructor.
     *
     * @param ActivatorConfiguratorInterface[] $configurators
     */
    public function __construct(array $configurators)
    {
        $this->configurators = $configurators;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('flagception');

        $rootNode
            ->children()
                ->arrayNode('features')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->booleanNode('default')
                                ->beforeNormalization()
                                    ->ifString()
                                    ->then(function ($value) {
                                        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
                                    })
                                ->end()
                                ->defaultFalse()
                            ->end()
                            ->scalarNode('env')
                                ->defaultFalse()
                            ->end()
                            ->booleanNode('cookie')
                                ->beforeNormalization()
                                    ->ifString()
                                    ->then(function ($value) {
                                        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
                                    })
                                ->end()
                                ->defaultTrue()
                            ->end()
                            ->scalarNode('constraint')
                                ->defaultFalse()
                            ->end()
                        ->end()
                    ->end()
                    ->defaultValue([])
                ->end()
                ->arrayNode('annotation')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enable')
                            ->beforeNormalization()
                                ->ifString()
                                ->then(function ($value) {
                                    return filter_var($value, FILTER_VALIDATE_BOOLEAN);
                                })
                            ->end()
                            ->defaultFalse()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('routing_metadata')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enable')
                            ->beforeNormalization()
                                ->ifString()
                                ->then(function ($value) {
                                    return filter_var($value, FILTER_VALIDATE_BOOLEAN);
                                })
                            ->end()
                            ->defaultTrue()
                        ->end()
                    ->end()
                ->end()
                ->append($this->appendActivators())
            ->end();

        return $treeBuilder;
    }

    /**
     * Add activators
     *
     * @return NodeDefinition
     */
    public function appendActivators()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('activators');
        $node->addDefaultsIfNotSet();

        $activatorNodeBuilder = $node->children();

        foreach ($this->configurators as $name => $configurator) {
            $configuratorNode = $activatorNodeBuilder->arrayNode($name)->canBeUnset();
            $configurator->addConfiguration($configuratorNode);
        }

        return $node;
    }
}
