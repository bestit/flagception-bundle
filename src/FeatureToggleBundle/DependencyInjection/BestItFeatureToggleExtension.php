<?php

namespace BestIt\FeatureToggleBundle\DependencyInjection;

use BestIt\FeatureToggleBundle\Stash\CookieStash;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
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
        if ($config['cookie_stash']['active']) {
            $definition = new Definition(
                CookieStash::class,
                [new Reference('request_stack'), $config['cookie_stash']['name']]
            );

            $definition->addTag('best_it_feature_toggle.stash', ['priority' => 255]);

            $container->setDefinition('best_it_feature_toggle.stash.cookie_stash', $definition);
        }

        // Enable / disable annotation subscriber
        if (!$config['use_annotation']) {
            $container->removeDefinition('best_it_feature_toggle.listener.annotation_subscriber');
        }
    }
}
