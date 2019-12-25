<?php

namespace Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator;

use Flagception\Activator\CacheActivator;
use Flagception\Database\Activator\DatabaseActivator;
use LogicException;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Configurator for database
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator
 */
class DatabaseConfigurator implements ActivatorConfiguratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return 'database';
    }

    /**
     * {@inheritdoc}
     */
    public function addActivator(ContainerBuilder $container, array $config, array $features)
    {
        if ($config['enable'] === false) {
            return;
        }

        if (!class_exists('Flagception\Database\Activator\DatabaseActivator')) {
            throw new LogicException('For using  database you have to load "flagception/database-activator"');
        }

        $definition = new Definition(DatabaseActivator::class);
        $credentials = null;
        if (isset($config['dbal'])) {
            $credentials = new Reference($config['dbal']);
        } elseif (isset($config['pdo'])) {
            $credentials = ['pdo' => new Reference($config['pdo'])];
        } elseif (isset($config['url'])) {
            $credentials = ['url' => $config['url']];
        } else {
            $credentials = $config['credentials'];
        }

        $definition->addArgument($credentials);
        $definition->addArgument($config['options']);

        $definition->addTag('flagception.activator', [
           'priority' => $config['priority']
        ]);

        $container->setDefinition('flagception.activator.database_activator', $definition);

        // Set caching
        if ($config['cache']['enable'] === true) {
            $cacheDefinition = new Definition(CacheActivator::class);
            $cacheDefinition->setDecoratedService('flagception.activator.database_activator');
            $cacheDefinition->addArgument(new Reference('flagception.activator.database_activator.cache.inner'));
            $cacheDefinition->addArgument(new Reference($config['cache']['pool']));
            $cacheDefinition->addArgument($config['cache']['lifetime']);
            $container->setDefinition('flagception.activator.database_activator.cache', $cacheDefinition);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addConfiguration(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet(['enable' => false])
            ->validate()
                ->ifTrue(function ($config) {
                    return !isset($config['url'])
                        && !isset($config['pdo'])
                        && !isset($config['dbal'])
                        && !isset($config['credentials']);
                })
                ->thenInvalid('You must either set the url, pdo, dbal or credentials field.')
            ->end()
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
                    ->defaultValue(220)
                ->end()
                ->scalarNode('url')
                    ->info('Connection string for the database')
                ->end()
                ->scalarNode('pdo')
                    ->info('Service with pdo instance')
                ->end()
                ->scalarNode('dbal')
                    ->info('Service with dbal instance')
                ->end()
                ->arrayNode('credentials')
                    ->children()
                        ->scalarNode('dbname')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('user')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('password')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('host')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('driver')
                            ->isRequired()
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('options')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('db_table')
                            ->defaultValue('flagception_features')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('db_column_feature')
                            ->defaultValue('feature')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('db_column_state')
                            ->defaultValue('state')
                            ->cannotBeEmpty()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('cache')
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
                        ->scalarNode('pool')
                            ->defaultValue('cache.app')
                            ->cannotBeEmpty()
                        ->end()
                        ->integerNode('lifetime')
                            ->defaultValue(3600)
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
