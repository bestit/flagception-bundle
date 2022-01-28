<?php

namespace Flagception\Bundle\FlagceptionBundle\DependencyInjection;

use Exception;
use Flagception\Activator\FeatureActivatorInterface;
use Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator\ActivatorConfiguratorInterface;
use Flagception\Decorator\ContextDecoratorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class FlagceptionExtension
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\DependencyInjection
 */
class FlagceptionExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('configurators.yml');
        $loader->load('services.yml');

        $configurators = $this->getConfigurators($container);
        $configuration = new Configuration($configurators);
        $config = $this->processConfiguration($configuration, $configs);

        // Enable / disable annotation subscriber
        if ($config['annotation']['enable'] === false) {
            $container->removeDefinition('flagception.listener.annotation_subscriber');
        }

        // Enable / disable routing metadata subscriber
        if ($config['routing_metadata']['enable'] === false) {
            $container->removeDefinition('flagception.listener.routing_metadata_subscriber');
        }

        // Enable / disable activators
        foreach ($configurators as $name => $configurator) {
            if (!array_key_exists($name, $config['activators'])) {
                continue;
            }

            $configurator->addActivator($container, $config['activators'][$name], $config['features']);
        }

        if (method_exists($container, 'registerForAutoconfiguration') === true) {
            $container
                ->registerForAutoconfiguration(FeatureActivatorInterface::class)
                ->addTag('flagception.activator');

            $container
                ->registerForAutoconfiguration(ContextDecoratorInterface::class)
                ->addTag('flagception.context_decorator');
        }
    }

    /**
     * Get configurators
     *
     * @param ContainerBuilder $container
     *
     * @return ActivatorConfiguratorInterface[]
     *
     * @throws Exception
     */
    private function getConfigurators(ContainerBuilder $container): array
    {
        $configurators = [];

        $services = $container->findTaggedServiceIds('flagception.configurator');
        foreach (array_keys($services) as $id) {
            $configurator = $container->get($id);
            $configurators[str_replace('-', '_', $configurator->getKey())] = $configurator;
        }

        return $configurators;
    }
}
