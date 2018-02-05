<?php

namespace Flagception\Tests\FlagceptionBundle\Model;

use Flagception\Bundle\FlagceptionBundle\Model\ResultGroup;
use PHPUnit\Framework\TestCase;

/**
 * Class ResultGroupTest
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Tests\FlagceptionBundle\Model
 */
class ResultGroupTest extends TestCase
{
    /**
     * Test add and get activators
     *
     * @return void
     */
    public function testAddAndGetActivators()
    {
        $group = new ResultGroup('feature_abc');
        $group->addActivator('config');
        $group->addActivator('foo');

        static::assertEquals([
            'config',
            'foo'
        ], $group->getActivators());
    }

    /**
     * Test feature name
     *
     * @return void
     */
    public function testGetFeatureName()
    {
        $group = new ResultGroup('feature_abc');

        static::assertEquals('feature_abc', $group->getFeatureName());
    }

    /**
     * Test requests
     *
     * @return void
     */
    public function testRequests()
    {
        $group = new ResultGroup('feature_abc');
        $group->increaseActive();
        $group->increaseActive();
        $group->increaseActive();

        $group->increaseInactive();
        $group->increaseInactive();

        static::assertEquals(3, $group->getAmountActive());
        static::assertEquals(2, $group->getAmountInactive());
        static::assertEquals(5, $group->getAmountRequests());
    }
}
