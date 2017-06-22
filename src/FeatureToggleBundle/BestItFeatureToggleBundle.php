<?php

namespace BestIt\FeatureToggleBundle;

use BestIt\FeatureToggleBundle\DependencyInjection\CompilerPass\StashPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class BestItFeatureToggleBundle
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle
 */
class BestItFeatureToggleBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new StashPass());
    }
}
