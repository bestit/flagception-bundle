<?php

namespace Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator;

use Flagception\Activator\CookieActivator;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

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

        $cookieFeatures = [];

        // Add only features which are allowed
        if ($config['mode'] === CookieActivator::WHITELIST) {
            $cookieFeatures = array_keys(array_filter($features, function ($feature) {
                return $feature['cookie'] === true;
            }));
        }

        // Add only features which are disallowed
        if ($config['mode'] === CookieActivator::BLACKLIST) {
            $cookieFeatures = array_keys(array_filter($features, function ($feature) {
                return $feature['cookie'] === false;
            }));
        }

        $definition = new Definition(CookieActivator::class);
        $definition->addArgument($cookieFeatures);
        $definition->addArgument($config['name']);
        $definition->addArgument($config['separator']);
        $definition->addArgument($config['mode']);

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
                ->enumNode('mode')
                    ->values([CookieActivator::WHITELIST, CookieActivator::BLACKLIST])
                    ->defaultValue(CookieActivator::WHITELIST)
                ->end()
            ->end();
    }
}
