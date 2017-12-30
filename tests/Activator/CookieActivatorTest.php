<?php

namespace Flagception\Tests\FlagceptionBundle\Activator;

use Flagception\Bundle\FlagceptionBundle\Activator\CookieActivator;
use Flagception\Activator\FeatureActivatorInterface;
use Flagception\Model\Context;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class CookieActivatorTest
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Tests\FlagceptionBundle\Activator
 */
class CookieActivatorTest extends TestCase
{
    /**
     * Test implement interface
     *
     * @return void
     */
    public function testImplementInterface()
    {
        $activator = new CookieActivator(
            [],
            'foo-cookie',
            ',',
            $this->createMock(RequestStack::class)
        );

        static::assertInstanceOf(FeatureActivatorInterface::class, $activator);
    }

    /**
     * Test name
     *
     * @return void
     */
    public function testName()
    {
        $activator = new CookieActivator(
            [],
            'foo-cookie',
            ',',
            $this->createMock(RequestStack::class)
        );

        static::assertEquals('cookie', $activator->getName());
    }

    /**
     * Test is not active if feature is not allowed for cookies
     *
     * @return void
     */
    public function testIsNotActiveIfDisabled()
    {
        $activator = new CookieActivator(
            [],
            'foo-cookie',
            ',',
            $this->createMock(RequestStack::class)
        );

        static::assertFalse($activator->isActive('feature_123', new Context()));
    }

    /**
     * Test is not active without master request
     *
     * @return void
     */
    public function testIsNotActiveWithoutRequest()
    {
        $activator = new CookieActivator(
            ['feature_123'],
            'foo-cookie',
            ',',
            $this->createMock(RequestStack::class)
        );

        static::assertFalse($activator->isActive('feature_123', new Context()));
    }

    /**
     * Test is not active without cookie
     *
     * @return void
     */
    public function testIsNotActiveWithoutCookie()
    {
        $activator = new CookieActivator(
            ['feature_123'],
            'foo-cookie',
            ',',
            $stack = new RequestStack()
        );

        $stack->push(new Request());

        static::assertFalse($activator->isActive('feature_123', new Context()));
    }

    /**
     * Test is active with one feature
     *
     * @return void
     */
    public function testIsActiveFeature()
    {
        $activator = new CookieActivator(
            ['feature_123'],
            'foo-cookie',
            ',',
            $stack = new RequestStack()
        );

        $stack->push(new Request([], [], [], ['foo-cookie' => 'feature_123']));

        static::assertTrue($activator->isActive('feature_123', new Context()));
    }

    /**
     * Test is active with multiple features
     *
     * @return void
     */
    public function testGetActiveFeatures()
    {
        $activator = new CookieActivator(
            ['feature_123'],
            'foo-cookie',
            ',',
            $stack = new RequestStack()
        );

        $stack->push(new Request([], [], [], ['foo-cookie' => 'feature_123,feature_abc,feature_784']));

        static::assertTrue($activator->isActive('feature_123', new Context()));
        static::assertFalse($activator->isActive('feature_784', new Context()));
        static::assertFalse($activator->isActive('feature_xyz', new Context()));
    }

    /**
     * Test is active with multiple features with whitespaces
     *
     * @return void
     */
    public function testGetActiveFeaturesWithWhitespaces()
    {
        $activator = new CookieActivator(
            ['feature_123', 'feature_784'],
            'foo-cookie',
            ',',
            $stack = new RequestStack()
        );

        $stack->push(new Request([], [], [], ['foo-cookie' => 'feature_123,    feature_abc, feature_784']));

        static::assertTrue($activator->isActive('feature_123', new Context()));
        static::assertTrue($activator->isActive('feature_784', new Context()));
        static::assertFalse($activator->isActive('feature_xyz', new Context()));
    }
}
