<?php

namespace Tests\BestIt\FeatureToggleBundle\Stash;

use BestIt\FeatureToggleBundle\Model\Context;
use BestIt\FeatureToggleBundle\Stash\CookieStash;
use BestIt\FeatureToggleBundle\Stash\StashInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class CookieStashTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Tests\BestIt\FeatureToggleBundle\Stash
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
        $stash = new CookieStash($this->createMock(RequestStack::class), 'foo-cookie', ',');
        static::assertInstanceOf(StashInterface::class, $stash);
    }

    /**
     * Test name
     *
     * @return void
     */
    public function testName()
    {
        $stash = new CookieStash($this->createMock(RequestStack::class), 'foo-cookie', ',');
        static::assertEquals('cookie', $stash->getName());
    }

    /**
     * Test is active without master request
     *
     * @return void
     */
    public function testIsActiveWithoutRequest()
    {
        $stash = new CookieStash(new RequestStack(), 'foo-cookie', ',');
        static::assertFalse($stash->isActive('feature_123', new Context()));
    }

    /**
     * Test is active without cookie
     *
     * @return void
     */
    public function testIsActiveWithoutCookie()
    {
        $stash = new CookieStash($stack = new RequestStack(), 'foo-cookie', ',');
        $stack->push(new Request());

        static::assertFalse($stash->isActive('feature_123', new Context()));
    }

    /**
     * Test is active with one feature
     *
     * @return void
     */
    public function testIsActiveFeature()
    {
        $stash = new CookieStash($stack = new RequestStack(), 'foo-cookie', ',');
        $stack->push(new Request([], [], [], ['foo-cookie' => 'feature_123']));

        static::assertTrue($stash->isActive('feature_123', new Context()));
    }

    /**
     * Test is active with multiple features
     *
     * @return void
     */
    public function testGetActiveFeatures()
    {
        $stash = new CookieStash($stack = new RequestStack(), 'foo-cookie', ',');
        $stack->push(new Request([], [], [], ['foo-cookie' => 'feature_123,feature_abc,feature_784']));

        static::assertTrue($stash->isActive('feature_123', new Context()));
        static::assertTrue($stash->isActive('feature_784', new Context()));
        static::assertFalse($stash->isActive('feature_xyz', new Context()));
    }

    /**
     * Test is active with multiple features with whitespaces
     *
     * @return void
     */
    public function testGetActiveFeaturesWithWhitespaces()
    {
        $stash = new CookieStash($stack = new RequestStack(), 'foo-cookie', ',');
        $stack->push(new Request([], [], [], ['foo-cookie' => 'feature_123,    feature_abc, feature_784']));

        static::assertTrue($stash->isActive('feature_123', new Context()));
        static::assertTrue($stash->isActive('feature_784', new Context()));
        static::assertFalse($stash->isActive('feature_xyz', new Context()));
    }
}
