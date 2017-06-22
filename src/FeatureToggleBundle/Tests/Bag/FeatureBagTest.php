<?php

namespace BestIt\FeatureToggleBundle\Tests\Bag;

use BestIt\FeatureToggleBundle\Bag\FeatureBag;
use BestIt\FeatureToggleBundle\Exception\FeatureNotFoundException;
use BestIt\FeatureToggleBundle\Model\Feature;
use PHPUnit\Framework\TestCase;
use Traversable;

/**
 * Class FeatureBagTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Tests\Bag
 */
class FeatureBagTest extends TestCase
{
    /**
     * Test add function
     *
     * @return void
     */
    public function testAdd()
    {
        $bag = new FeatureBag();
        $bag->add('feature_1', true);
        $bag->add('feature_2', false);

        $feature = $bag->get('feature_2');
        static::assertEquals('feature_2', $feature->getName());
        static::assertEquals(false, $feature->isActive());
    }

    /**
     * Test has function
     *
     * @return void
     */
    public function testHas()
    {
        $bag = new FeatureBag();
        $bag->add('feature_1', true);
        $bag->add('feature_2', false);

        static::assertEquals(true, $bag->has('feature_2'));
        static::assertEquals(false, $bag->has('feature_3'));
    }

    /**
     * Test get function
     *
     * @return void
     */
    public function testGet()
    {
        $bag = new FeatureBag();
        $bag->add('feature_1', true);
        $bag->add('feature_2', false);

        $feature = $bag->get('feature_2');
        static::assertEquals('feature_2', $feature->getName());
        static::assertEquals(false, $feature->isActive());
    }

    /**
     * Test get function
     *
     * @return void
     */
    public function testGetMissingFeature()
    {
        $this->expectException(FeatureNotFoundException::class);

        $bag = new FeatureBag();
        $bag->add('feature_1', true);
        $bag->add('feature_2', false);

        $bag->get('feature_3');
    }

    /**
     * Test iteration
     *
     * @return void
     */
    public function testIteration()
    {
        $bag = new FeatureBag();
        $bag->add('feature_1', true);
        $bag->add('feature_2', false);
        $bag->add('feature_3', false);

        static::assertInstanceOf(Traversable::class, $bag);

        foreach ($bag as $name => $feature) {
            static::assertInstanceOf(Feature::class, $feature);
            static::assertEquals($name, $feature->getName());
        }
    }

    /**
     * Test get all
     *
     * @return void
     */
    public function testAll()
    {
        $bag = new FeatureBag();
        $bag->add('feature_1', true);
        $bag->add('feature_2', false);
        $bag->add('feature_3', false);

        static::assertInstanceOf(Traversable::class, $bag);

        foreach ($bag->all() as $name => $feature) {
            static::assertInstanceOf(Feature::class, $feature);
            static::assertEquals($name, $feature->getName());
        }
    }
}
