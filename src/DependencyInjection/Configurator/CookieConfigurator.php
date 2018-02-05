<?php

namespace Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator;

use Flagception\Bundle\FlagceptionBundle\Activator\CookieActivator;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class CookieConfigurator
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator
 */
class CookieConfigurator implements ActivatorConfiguratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return 'cookie';
    }

    /**
     * {@inheritdoc}
     */
    public function addActivator(ContainerBuilder $container, array $config, array $features)
    {
        if ($config['enable'] === false) {
            return;
        }

        $cookieFeatures = array_keys(array_filter($features, function ($feature) {
            return $feature['cookie'] === true;
        }));

        $definition = new Definition(CookieActivator::class);
        $definition->addArgument($cookieFeatures);
        $definition->addArgument($config['name']);
        $definition->addArgument($config['separator']);
        $definition->addArgument(new Reference('request_stack'));

        $definition->addTag('flagception.activator', [
           'priority' => $config['priority']
        ]);

        $container->setDefinition('flagception.activator.cookie_activator', $definition);
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
                    ->defaultValue(200)
                ->end()
                ->scalarNode('name')
                    ->defaultValue('flagception')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('separator')
                    ->defaultValue(',')
                    ->cannotBeEmpty()
                ->end()
            ->end();
    }
}
