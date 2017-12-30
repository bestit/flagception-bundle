<?php

namespace Flagception\Tests\FlagceptionBundle\DependencyInjection\CompilerPass;

use Flagception\Bundle\FlagceptionBundle\DependencyInjection\CompilerPass\ExpressionProviderPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ExpressionProviderPassTest
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Tests\FlagceptionBundle\DependencyInjection\CompilerPass
 */
class ExpressionProviderPassTest extends TestCase
{
    /**
     * Test process
     *
     * @return void
     */
    public function testProcess()
    {
        $container = new ContainerBuilder();

        $factory = $this->createMock(Definition::class);
        $container->setDefinition('flagception.factory.expression_language_factory', $factory);

        $factory
            ->expects(static::exactly(3))
            ->method('addMethodCall')
            ->withConsecutive(
                [static::equalTo('addProvider'), static::equalTo([new Reference('foo')])],
                [static::equalTo('addProvider'), static::equalTo([new Reference('bar')])],
                [static::equalTo('addProvider'), static::equalTo([new Reference('bazz')])]
            );

        $container->setDefinition(
            'foo',
            (new Definition(__CLASS__))->addTag('flagception.expression_language_provider')
        );
        $container->setDefinition(
            'bar',
            (new Definition(__CLASS__))->addTag('flagception.expression_language_provider')
        );
        $container->setDefinition(
            'bazz',
            (new Definition(__CLASS__))->addTag('flagception.expression_language_provider')
        );

        (new ExpressionProviderPass())->process($container);
    }
}
