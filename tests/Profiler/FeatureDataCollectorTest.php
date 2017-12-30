<?php

namespace Flagception\Tests\FlagceptionBundle\Profiler;

use Flagception\Bundle\FlagceptionBundle\Bag\FeatureResultBag;
use Flagception\Bundle\FlagceptionBundle\Model\Result;
use Flagception\Model\Context;
use Flagception\Bundle\FlagceptionBundle\Profiler\FeatureDataCollector;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class FeatureDataCollectorTest
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Tests\FlagceptionBundle\Profiler
 */
class FeatureDataCollectorTest extends TestCase
{
    /**
     * Test get name
     *
     * @return void
     */
    public function testGetName()
    {
        $collector = new FeatureDataCollector(new FeatureResultBag());
        static::assertEquals('flagception', $collector->getName());
    }

    /**
     * Test the reset method
     *
     * @return void
     */
    public function testReset()
    {
        $collector = new FeatureDataCollector($bag = new FeatureResultBag());
        $bag->add(new Result('feature_abc', true, new Context(), 'config'));

        static::assertEquals(1, count($bag->all()));

        $collector->reset();
        static::assertEquals(0, count($bag->all()));
    }

    /**
     * Test complete collect handling
     *
     * @return void
     */
    public function testCollect()
    {
        $bag = new FeatureResultBag();
        $bag->add($result1 = new Result('feature_abc', true, new Context(), 'config'));
        $bag->add($result1 = new Result('feature_abc', true, new Context(), 'array'));
        $bag->add($result2 = new Result('feature_abc', false, new Context(), 'config'));
        $bag->add($result3 = new Result('feature_def', true, new Context(), 'config'));

        $collector = new FeatureDataCollector($bag);
        $collector->collect(new Request(), new Response());

        static::assertEquals($bag->all(), $collector->getResults());

        $resultGroups = $collector->getGroupedResults();
        static::assertEquals('feature_def', $resultGroups['feature_def']->getFeatureName());
        static::assertEquals(1, $resultGroups['feature_def']->getAmountRequests());
        static::assertEquals(0, $resultGroups['feature_def']->getAmountInactive());
        static::assertEquals(1, $resultGroups['feature_def']->getAmountActive());
        static::assertEquals(['config'], $resultGroups['feature_def']->getActivators());

        static::assertEquals('feature_abc', $resultGroups['feature_abc']->getFeatureName());
        static::assertEquals(3, $resultGroups['feature_abc']->getAmountRequests());
        static::assertEquals(1, $resultGroups['feature_abc']->getAmountInactive());
        static::assertEquals(2, $resultGroups['feature_abc']->getAmountActive());
        static::assertEquals(['config', 'array'], $resultGroups['feature_abc']->getActivators());
    }
}
