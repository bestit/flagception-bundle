<?php

namespace Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator;

use Flagception\Bundle\FlagceptionBundle\Activator\RoleActivator;
use LogicException;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Configurator for role activator
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator
 */
class RoleConfigurator implements ActivatorConfiguratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return 'role';
    }

    /**
     * {@inheritdoc}
     */
    public function addActivator(ContainerBuilder $container, array $config, array $features)
    {
        if ($config['enable'] === false) {
            return;
        }

        $bundles = $container->getParameter('kernel.bundles');
        if (!array_key_exists('SecurityBundle', $bundles)) {
            throw new LogicException('For using roles you have to load "symfony/security-bundle"');
        }

        $roles = [];
        foreach ($features as $name => $setting) {
            if (array_key_exists('roles', $setting)) {
                $roles[$name] = $setting['roles'];
            }
        }

        $definition = new Definition(RoleActivator::class);
        $definition->addArgument(new Reference('security.token_storage'));
        $definition->addArgument($roles);

        $definition->addTag('flagception.activator', [
           'priority' => $config['priority']
        ]);

        $container->setDefinition('flagception.activator.role_activator', $definition);
    }

    /**
     * {@inheritdoc}
     */
    public function addConfiguration(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet(['enable' => false])
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
                    ->defaultValue(250)
                ->end()
            ->end();
    }
}
