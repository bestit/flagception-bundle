<?php

namespace Flagception\Bundle\FlagceptionBundle;

use Flagception\Bundle\FlagceptionBundle\DependencyInjection\CompilerPass\ActivatorPass;
use Flagception\Bundle\FlagceptionBundle\DependencyInjection\CompilerPass\ContextDecoratorPass;
use Flagception\Bundle\FlagceptionBundle\DependencyInjection\CompilerPass\ExpressionProviderPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class FlagceptionBundle
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle
 */
class FlagceptionBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ActivatorPass());
        $container->addCompilerPass(new ContextDecoratorPass());
        $container->addCompilerPass(new ExpressionProviderPass());
    }
}
