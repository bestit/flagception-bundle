<?php

namespace BestIt\FeatureToggleBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class BestItFeatureToggleExtension
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\DependencyInjection
 */
class BestItFeatureToggleExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Add parameters
        $container->setParameter(
            'best_it_feature_toggle.config.cookie_stash_name',
            $config['cookie_stash']['name']
        );

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        // Add features for config stash
        $bag = $container->getDefinition('best_it_feature_toggle.stash.config_stash');
        foreach ($config['features'] as $name => $feature) {
            if ($feature['active']) {
                $bag->addMethodCall('add', [$name, $feature['active']]);
            }
        }

        // Enable / disable cookie stash
        if ($config['cookie_stash']['active'] === false) {
            $container->removeDefinition('best_it_feature_toggle.stash.cookie_stash');
        }

        // Enable / disable annotation subscriber
        if ($config['annotation']['active'] === false) {
            $container->removeDefinition('best_it_feature_toggle.listener.annotation_subscriber');
        }

        // Enable / disable routing metadata subscriber
        if ($config['routing_metadata']['active'] === false) {
            $container->removeDefinition('best_it_feature_toggle.listener.routing_metadata_subscriber');
        }

        // Enable / disable annotation subscriber
        if (!$config['use_annotation']) {
            $container->removeDefinition('best_it_feature_toggle.listener.annotation_subscriber');
        }
    }
}
