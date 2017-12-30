<?php

namespace Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator;

use Flagception\Activator\EnvironmentActivator;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Class EnvironmentConfigurator
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator
 */
class EnvironmentConfigurator implements ActivatorConfiguratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return 'environment';
    }

    /**
     * {@inheritdoc}
     */
    public function addActivator(ContainerBuilder $container, array $config, array $features)
    {
        if ($config['enable'] === false) {
            return;
        }

        $filteredFeatures = array_filter($features, function ($feature) {
            return $feature['env'] !== false;
        });

        $environmentVariables = [];
        foreach ($filteredFeatures as $name => $value) {
            $environmentVariables[$name] = $value['env'];
        }

        $definition = new Definition(EnvironmentActivator::class);
        $definition->addArgument($environmentVariables);

        $definition->addTag('flagception.activator', [
           'priority' => $config['priority']
        ]);

        $container->setDefinition('flagception.activator.environment_activator', $definition);
    }

    /**
     * {@inheritdoc}
     */
    public function addConfiguration(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet(['enable' => true])
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
                ->integerNode('priority')
                    ->defaultValue(230)
                ->end()
            ->end();
    }
}
