<?php

namespace Flagception\Tests\FlagceptionBundle\Activator;

use Flagception\Activator\ChainActivator;
use Flagception\Activator\CookieActivator;
use Flagception\Activator\EnvironmentActivator;
use Flagception\Bundle\FlagceptionBundle\Activator\TraceableChainActivator;
use Flagception\Activator\ArrayActivator;
use Flagception\Activator\FeatureActivatorInterface;
use Flagception\Model\Context;
use PHPUnit\Framework\TestCase;

/**
 * Tests the TraceableChainActivator
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Tests\FlagceptionBundle\Activator
 */
class TraceableChainActivatorTest extends TestCase
{
    /**
     * Test implement interface
     *
     * @return void
     */
    public function testImplementInterface()
    {
        $activator = new TraceableChainActivator();
        static::assertInstanceOf(FeatureActivatorInterface::class, $activator);
    }

    /**
     * Test extends chain activator
     *
     * @return void
     */
    public function testExtendsChainActivator()
    {
        $activator = new TraceableChainActivator();
        static::assertInstanceOf(ChainActivator::class, $activator);
    }

    /**
     * Test name
     *
     * @return void
     */
    public function testName()
    {
        $activator = new TraceableChainActivator();
        static::assertEquals('chain', $activator->getName());
    }

    /**
     * Test with no activators
     *
     * @return void
     */
    public function testNoActivators()
    {
        $activator = new TraceableChainActivator();
        static::assertFalse($activator->isActive('feature_abc', new Context()));
    }

    /**
     * Test no activators return true
     *
     * @return void
     */
    public function testNoActivatorsReturnTrue()
    {
        $activator = new TraceableChainActivator();
        $activator->add(new ArrayActivator([
            'feature_def'
        ]));
        $activator->add(new CookieActivator([
            'feature_ghi'
        ]));

        static::assertFalse($activator->isActive('feature_abc', $context = new Context()));

        static::assertEquals([
            [
                'feature' => 'feature_abc',
                'context' => $context,
                'result' => false,
                'stack' => [
                    'array' => false,
                    'cookie' => false
                ]
            ]
        ], $activator->getTrace());
    }

    /**
     * Test one activator return true
     *
     * @return void
     */
    public function testOneActivatorsReturnTrue()
    {
        $activator = new TraceableChainActivator();
        $activator->add(new CookieActivator([
            'feature_def'
        ]));
        $activator->add(new ArrayActivator([
            'feature_abc'
        ]));
        $activator->add(new EnvironmentActivator([
            'feature_hij'
        ]));

        static::assertTrue($activator->isActive('feature_abc', $context = new Context()));

        static::assertEquals([
            [
                'feature' => 'feature_abc',
                'context' => $context,
                'result' => true,
                'stack' => [
                    'cookie' => false,
                    'array' => true
                ]
            ]
        ], $activator->getTrace());
    }

    /**
     * Test add and get activators
     *
     * @return void
     */
    public function testAddAndGet()
    {
        $decorator = new TraceableChainActivator();
        $decorator->add($fakeActivator1 = new ArrayActivator());
        $decorator->add($fakeActivator2 = new ArrayActivator([]));

        // Should be the same sorting
        static::assertSame($fakeActivator1, $decorator->getActivators()[0]);
        static::assertSame($fakeActivator2, $decorator->getActivators()[1]);
    }
}
