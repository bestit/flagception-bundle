<?php

namespace Tests\BestIt\FeatureToggleBundle\DependencyInjection\Test;

use BestIt\FeatureToggleBundle\DependencyInjection\CompilerPass\ContextDecoratorPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ContextDecoratorPassTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Tests\BestIt\FeatureToggleBundle\DependencyInjection\Test
 */
class ContextDecoratorPassTest extends TestCase
{
    /**
     * Test process
     */
    public function testProcess()
    {
        $container = new ContainerBuilder();

        $bag = $this->createMock(Definition::class);
        $container->setDefinition('best_it_feature_toggle.bag.context_decorator_bag', $bag);

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
            (new Definition(__CLASS__))->addTag('best_it_feature_toggle.context_decorator', [
                'priority' => 255
            ])
        );
        $container->setDefinition(
            'bar',
            (new Definition(__CLASS__))->addTag('best_it_feature_toggle.context_decorator')
        );
        $container->setDefinition(
            'bazz',
            (new Definition(__CLASS__))->addTag('best_it_feature_toggle.context_decorator', [
                'priority' => 25
            ])
        );

        (new ContextDecoratorPass())->process($container);
    }
}
