<?php

namespace Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator;

use Flagception\Contentful\Activator\ContentfulActivator;
use LogicException;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ContentfulConfigurator
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator
 */
class ContentfulConfigurator implements ActivatorConfiguratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return 'contentful';
    }

    /**
     * {@inheritdoc}
     */
    public function addActivator(ContainerBuilder $container, array $config, array $features)
    {
        if ($config['enable'] === false) {
            return;
        }

        if (!class_exists('Flagception\Contentful\Activator\ContentfulActivator')) {
            throw new LogicException('For using contentful you have to load "flagception/contentful-activator"');
        }

        $definition = new Definition(ContentfulActivator::class);
        $definition->addArgument(new Reference($config['client_id']));
        $definition->addArgument($config['content_type']);
        $definition->addArgument($config['mapping']);

        $definition->addTag('flagception.activator', [
           'priority' => $config['priority']
        ]);

        $container->setDefinition('flagception.activator.contentful_activator', $definition);
    }

    /**
     * {@inheritdoc}
     */
    public function addConfiguration(ArrayNodeDefinition $node)
    {
        $node
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
                ->integerNode('priority')
                    ->defaultValue(150)
                ->end()
                ->scalarNode('client_id')
                    ->info('Contentful client service id')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('content_type')
                    ->defaultValue('flagception')
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('mapping')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('name')
                            ->defaultValue('name')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('state')
                            ->defaultValue('state')
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
