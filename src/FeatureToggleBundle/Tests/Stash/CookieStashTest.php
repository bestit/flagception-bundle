<?php

namespace BestIt\FeatureToggleBundle\Tests\Stash;

use BestIt\FeatureToggleBundle\Stash\CookieStash;
use BestIt\FeatureToggleBundle\Stash\StashInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class CookieStashTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Tests\Stash
 */
class CookieStashTest extends TestCase
{
    /**
     * Test implement interface
     *
     * @return void
     */
    public function testImplementInterface()
    {
        $stash = new CookieStash($this->createMock(RequestStack::class), 'foo-cookie');
        static::assertInstanceOf(StashInterface::class, $stash);
    }

    /**
     * Test name
     *
     * @return void
     */
    public function testName()
    {
        $stash = new CookieStash($this->createMock(RequestStack::class), 'foo-cookie');
        static::assertEquals('cookie', $stash->getName());
    }

    /**
     * Test get active features without master request
     *
     * @return void
     */
    public function testGetActiveFeaturesWithoutRequest()
    {
        $stash = new CookieStash(new RequestStack(), 'foo-cookie');
        static::assertEquals([], $stash->getActiveFeatures());
    }

    /**
     * Test get active features without cookie
     *
     * @return void
     */
    public function testGetActiveFeaturesWithoutCookie()
    {
        $stash = new CookieStash($stack = new RequestStack(), 'foo-cookie');
        $stack->push(new Request());

        static::assertEquals([], $stash->getActiveFeatures());
    }

    /**
     * Test get one active features
     *
     * @return void
     */
    public function testGetOneActiveFeature()
    {
        $stash = new CookieStash($stack = new RequestStack(), 'foo-cookie');
        $stack->push(new Request([], [], [], ['foo-cookie' => 'feature_123']));

        static::assertEquals(['feature_123'], $stash->getActiveFeatures());
    }

    /**
     * Test get active features
     *
     * @return void
     */
    public function testGetActiveFeatures()
    {
        $stash = new CookieStash($stack = new RequestStack(), 'foo-cookie');
        $stack->push(new Request([], [], [], ['foo-cookie' => 'feature_123|feature_abc|feature_784']));

        static::assertEquals(['feature_123', 'feature_abc', 'feature_784'], $stash->getActiveFeatures());
    }
}
