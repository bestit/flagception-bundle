<?php

namespace Tests\BestIt\FeatureToggleBundle\Stash;

use BestIt\FeatureToggleBundle\Model\Context;
use BestIt\FeatureToggleBundle\Stash\ArrayStash;
use BestIt\FeatureToggleBundle\Stash\StashInterface;
use PHPUnit\Framework\TestCase;

/**
 * Test class for ArrayStash
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Tests\BestIt\FeatureToggleBundle\Stash
 */
class ArrayStashTest extends TestCase
{
    /**
     * Test implement interface
     *
     * @return void
     */
    public function testImplementInterface()
    {
        $stash = new ArrayStash();
        static::assertInstanceOf(StashInterface::class, $stash);
    }

    /**
     * Test name
     *
     * @return void
     */
    public function testName()
    {
        $stash = new ArrayStash();
        static::assertEquals('array', $stash->getName());
    }

    /**
     * Test is not active
     *
     * @return void
     */
    public function testIsNotActive()
    {
        $stash = new ArrayStash();
        static::assertFalse($stash->isActive('bar', new Context()));
    }

    /**
     * Test is active
     *
     * @return void
     */
    public function testIsActive()
    {
        $stash = new ArrayStash(['foo', 'bar', 'baz']);
        static::assertTrue($stash->isActive('bar', new Context()));
        static::assertFalse($stash->isActive('not-active-feature', new Context()));
    }
}
