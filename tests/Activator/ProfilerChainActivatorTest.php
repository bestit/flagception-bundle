<?php

namespace Flagception\Tests\FlagceptionBundle\Activator;

use Flagception\Activator\ChainActivator;
use Flagception\Bundle\FlagceptionBundle\Activator\ProfilerChainActivator;
use Flagception\Bundle\FlagceptionBundle\Bag\FeatureResultBag;
use Flagception\Bundle\FlagceptionBundle\Model\Result;
use Flagception\Activator\ArrayActivator;
use Flagception\Activator\FeatureActivatorInterface;
use Flagception\Model\Context;
use PHPUnit\Framework\TestCase;

/**
 * Class ProfilerChainActivatorTest
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Tests\FlagceptionBundle\Activator
 */
class ProfilerChainActivatorTest extends TestCase
{
    /**
     * Test implement interface
     *
     * @return void
     */
    public function testImplementInterface()
    {
        $activator = new ProfilerChainActivator(new FeatureResultBag());
        static::assertInstanceOf(FeatureActivatorInterface::class, $activator);
    }

    /**
     * Test extends change activator
     *
     * @return void
     */
    public function testExtendsChainActivator()
    {
        $activator = new ProfilerChainActivator(new FeatureResultBag());
        static::assertInstanceOf(ChainActivator::class, $activator);
    }

    /**
     * Test name
     *
     * @return void
     */
    public function testName()
    {
        $activator = new ProfilerChainActivator(new FeatureResultBag());
        static::assertEquals('profiler_chain', $activator->getName());
    }

    /**
     * Test with no activators
     *
     * @return void
     */
    public function testNoActivators()
    {
        $activator = new ProfilerChainActivator(new FeatureResultBag());
        static::assertFalse($activator->isActive('feature_abc', new Context()));
    }

    /**
     * Test no activators return true
     *
     * @return void
     */
    public function testNoActivatorsReturnTrue()
    {
        $activator = new ProfilerChainActivator($bag = new FeatureResultBag());
        $activator->add(new ArrayActivator([
            'feature_def'
        ]));
        $activator->add(new ArrayActivator([
            'feature_ghi'
        ]));

        static::assertFalse($activator->isActive('feature_abc', $context = new Context()));

        static::assertEquals([
            new Result('feature_abc', false, $context, 'array'),
            new Result('feature_abc', false, $context, 'array')
        ], $bag->all());
    }

    /**
     * Test one activator return true
     *
     * @return void
     */
    public function testOneActivatorsReturnTrue()
    {
        $activator = new ProfilerChainActivator($bag = new FeatureResultBag());
        $activator->add(new ArrayActivator([
            'feature_def'
        ]));
        $activator->add(new ArrayActivator([
            'feature_abc'
        ]));
        $activator->add(new ArrayActivator([
            'feature_hij'
        ]));

        static::assertTrue($activator->isActive('feature_abc', $context = new Context()));

        static::assertEquals([
            new Result('feature_abc', false, $context, 'array'),
            new Result('feature_abc', true, $context, 'array')
        ], $bag->all());
    }

    /**
     * Test add and get activators
     *
     * @return void
     */
    public function testAddAndGet()
    {
        $decorator = new ProfilerChainActivator(new FeatureResultBag());
        $decorator->add($fakeActivator1 = new ArrayActivator());
        $decorator->add($fakeActivator2 = new ArrayActivator([]));

        // Should be the same sorting
        static::assertSame($fakeActivator1, $decorator->getActivators()[0]);
        static::assertSame($fakeActivator2, $decorator->getActivators()[1]);
    }
}
