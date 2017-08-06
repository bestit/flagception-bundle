<?php

namespace Tests\BestIt\FeatureToggleBundle\DependencyInjection\Test;

use BestIt\FeatureToggleBundle\DependencyInjection\CompilerPass\StashPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class StashPassTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Tests\BestIt\FeatureToggleBundle\DependencyInjection\Test
 */
class StashPassTest extends TestCase
{
    /**
     * Test process
     */
    public function testProcess()
    {
        $container = new ContainerBuilder();

        $bag = $this->createMock(Definition::class);
        $container->setDefinition('best_it_feature_toggle.bag.stash_bag', $bag);

        $bag
            ->expects(static::exactly(3))
            ->method('addMethodCall')
            ->withConsecutive(
                [static::equalTo('add'), static::equalTo([new Reference('foo')])],
                [static::equalTo('add'), static::equalTo([new Reference('bazz')])],
                [static::equalTo('add'), static::equalTo([new Reference('bar')])]
            );

        $container->setDefinition('foo', (new Definition(__CLASS__))->addTag('best_it_feature_toggle.stash', [
            'priority' => 255
        ]));
        $container->setDefinition('bar', (new Definition(__CLASS__))->addTag('best_it_feature_toggle.stash'));
        $container->setDefinition('bazz', (new Definition(__CLASS__))->addTag('best_it_feature_toggle.stash', [
            'priority' => 25
        ]));

        (new StashPass())->process($container);
    }
}
