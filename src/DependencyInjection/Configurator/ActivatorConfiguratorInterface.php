<?php

namespace Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Interface ActivatorConfiguratorInterface
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator
 */
interface ActivatorConfiguratorInterface
{
    /**
     * Get configurator key
     *
     * @return string
     */
    public function getKey();

    /**
     * Add activator
     *
     * @param ContainerBuilder $container
     * @param array $config
     * @param array $features
     *
     * @return void
     */
    public function addActivator(ContainerBuilder $container, array $config, array $features);

    /**
     * Add configuration to node
     *
     * @param ArrayNodeDefinition $node
     *
     * @return void
     */
    public function addConfiguration(ArrayNodeDefinition $node);
}
