<?php

namespace Flagception\Tests\FlagceptionBundle\DependencyInjection\CompilerPass;

use Flagception\Bundle\FlagceptionBundle\DependencyInjection\CompilerPass\ContextDecoratorPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ContextDecoratorPassTest
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Tests\FlagceptionBundle\DependencyInjection\CompilerPass
 */
class ContextDecoratorPassTest extends TestCase
{
    /**
     * Test process
     *
     * @return void
     */
    public function testProcess()
    {
        $container = new ContainerBuilder();

        $bag = $this->createMock(Definition::class);
        $container->setDefinition('flagception.decorator.chain_decorator', $bag);

        $bag
            ->expects(static::exactly(3))
            ->method('addMethodCall')
            ->withConsecutive(
                [static::equalTo('add'), static::equalTo([new Reference('foo')])],
                [static::equalTo('add'), static::equalTo([new Reference('bazz')])],
                [static::equalTo('add'), static::equalTo([new Reference('bar')])]
            );

        $container->setDefinition(
            'foo',
            (new Definition(__CLASS__))->addTag('flagception.context_decorator', [
                'priority' => 255
            ])
        );
        $container->setDefinition(
            'bar',
            (new Definition(__CLASS__))->addTag('flagception.context_decorator')
        );
        $container->setDefinition(
            'bazz',
            (new Definition(__CLASS__))->addTag('flagception.context_decorator', [
                'priority' => 25
            ])
        );

        (new ContextDecoratorPass())->process($container);
    }
}
