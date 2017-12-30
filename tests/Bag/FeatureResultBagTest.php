<?php

namespace Flagception\Tests\FlagceptionBundle\Bag;

use Flagception\Bundle\FlagceptionBundle\Bag\FeatureResultBag;
use Flagception\Bundle\FlagceptionBundle\Model\Result;
use Flagception\Model\Context;
use PHPUnit\Framework\TestCase;

/**
 * Class FeatureResultBagTest
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Tests\FlagceptionBundle\Bag
 */
class FeatureResultBagTest extends TestCase
{
    /**
     * Test add function
     *
     * @return void
     */
    public function testAdd()
    {
        $bag = new FeatureResultBag();
        $bag->add($result = new Result('feature_1', true, new Context(), 'config'));

        static::assertSame([
            $result
        ], $bag->all());
    }

    /**
     * Test get all
     *
     * @return void
     */
    public function testAll()
    {
        $bag = new FeatureResultBag();
        $bag->add($result1 = new Result('feature_1', true, new Context(), 'config'));
        $bag->add($result2 = new Result('feature_2', true, new Context(), 'config'));
        $bag->add($result3 = new Result('feature_3', true, new Context(), 'config'));

        static::assertSame([
            $result1,
            $result2,
            $result3
        ], $bag->all());
    }

    /**
     * Test clear function
     *
     * @return void
     */
    public function testClear()
    {
        $bag = new FeatureResultBag();
        $bag->add($result = new Result('feature_1', true, new Context(), 'config'));

        static::assertSame(1, count($bag->all()));

        $bag->clear();
        static::assertSame(0, count($bag->all()));
    }
}
