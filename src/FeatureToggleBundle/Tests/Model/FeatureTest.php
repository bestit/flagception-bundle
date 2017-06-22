<?php

namespace BestIt\FeatureToggleBundle\Tests\Model;

use BestIt\FeatureToggleBundle\Model\Feature;
use PHPUnit\Framework\TestCase;

/**
 * Class FeatureTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Tests\Model
 */
class FeatureTest extends TestCase
{
    /**
     * Test get name
     *
     * @return void
     */
    public function testName()
    {
        $feature = new Feature('foo-name', true);

        static::assertEquals('foo-name', $feature->getName());
    }

    /**
     * Test get active state
     *
     * @return void
     */
    public function testActive()
    {
        $feature = new Feature('foo-name', true);

        static::assertEquals(true, $feature->isActive());
    }

    /**
     * Test enable function
     *
     * @return void
     */
    public function testEnable()
    {
        $feature = new Feature('foo-name', false);
        static::assertEquals(false, $feature->isActive());

        $feature->enable();
        static::assertEquals(true, $feature->isActive());
    }

    /**
     * Test disable function
     *
     * @return void
     */
    public function testDisable()
    {
        $feature = new Feature('foo-name', true);
        static::assertEquals(true, $feature->isActive());

        $feature->disable();
        static::assertEquals(false, $feature->isActive());
    }
}
