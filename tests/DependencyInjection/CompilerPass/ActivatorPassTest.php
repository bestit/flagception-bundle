<?php

namespace Flagception\Tests\FlagceptionBundle\DependencyInjection\CompilerPass;

use Flagception\Bundle\FlagceptionBundle\DependencyInjection\CompilerPass\ActivatorPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ActivatorPassTest
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Tests\FlagceptionBundle\DependencyInjection\CompilerPass
 */
class ActivatorPassTest extends TestCase
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
        $container->setDefinition('flagception.activator.chain_activator', $bag);

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
            (new Definition(__CLASS__))->addTag('flagception.activator', [
                'priority' => 255
            ])
        );
        $container->setDefinition(
            'bar',
            (new Definition(__CLASS__))->addTag('flagception.activator')
        );
        $container->setDefinition(
            'bazz',
            (new Definition(__CLASS__))->addTag('flagception.activator', [
                'priority' => 25
            ])
        );

        (new ActivatorPass())->process($container);
    }
}
