<?php

namespace Flagception\Tests\FlagceptionBundle;

use Flagception\Bundle\FlagceptionBundle\DependencyInjection\CompilerPass\ActivatorPass;
use Flagception\Bundle\FlagceptionBundle\DependencyInjection\CompilerPass\ContextDecoratorPass;
use Flagception\Bundle\FlagceptionBundle\DependencyInjection\CompilerPass\ExpressionProviderPass;
use Flagception\Bundle\FlagceptionBundle\FlagceptionBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class FlagceptionBundleTest
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Tests\FlagceptionBundle
 */
class FlagceptionBundleTest extends TestCase
{
    /**
     * Test build method
     *
     * @return void
     */
    public function testBuild()
    {
        $bundle = new FlagceptionBundle();

        $builder = $this->createMock(ContainerBuilder::class);
        $builder
            ->expects(static::exactly(3))
            ->method('addCompilerPass')
            ->withConsecutive(
                [static::isInstanceOf(ActivatorPass::class)],
                [static::isInstanceOf(ContextDecoratorPass::class)],
                [static::isInstanceOf(ExpressionProviderPass::class)]
            );

        $bundle->build($builder);
    }
}
