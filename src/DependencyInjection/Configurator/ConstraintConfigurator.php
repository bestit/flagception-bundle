<?php

namespace Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator;

use Flagception\Activator\ConstraintActivator;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ConstraintConfigurator
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator
 */
class ConstraintConfigurator implements ActivatorConfiguratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return 'constraint';
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
            return $feature['constraint'] !== false;
        });

        $constraintVariables = [];
        foreach ($filteredFeatures as $name => $value) {
            $constraintVariables[$name] = $value['constraint'];
        }

        $definition = new Definition(ConstraintActivator::class);
        $definition->addArgument(new Reference('flagception.constraint.constraint_resolver'));
        $definition->addArgument($constraintVariables);

        $definition->addTag('flagception.activator', [
           'priority' => $config['priority']
        ]);

        $container->setDefinition('flagception.activator.constraint_activator', $definition);
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
                    ->defaultValue(210)
                ->end()
            ->end();
    }
}
