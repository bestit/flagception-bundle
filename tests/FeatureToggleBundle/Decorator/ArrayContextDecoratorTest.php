<?php

namespace Tests\BestIt\FeatureToggleBundle\Decorator;

use BestIt\FeatureToggleBundle\Decorator\ContextDecoratorInterface;
use BestIt\FeatureToggleBundle\Decorator\ArrayContextDecorator;
use BestIt\FeatureToggleBundle\Model\Context;
use PHPUnit\Framework\TestCase;

/**
 * Test class for ArrayContextDecorator
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Tests\BestIt\FeatureToggleBundle\Decorator
 */
class ArrayContextDecoratorTest extends TestCase
{
    /**
     * Test implement interface
     *
     * @return void
     */
    public function testImplementInterface()
    {
        $decorator = new ArrayContextDecorator();
        static::assertInstanceOf(ContextDecoratorInterface::class, $decorator);
    }

    /**
     * Test name
     *
     * @return void
     */
    public function testName()
    {
        $decorator = new ArrayContextDecorator();
        static::assertEquals('array', $decorator->getName());
    }

    /**
     * Test decorate
     *
     * @return void
     */
    public function testDecorate()
    {
        $decorator = new ArrayContextDecorator(['baz' => 'apple']);

        $context = new Context();
        $context->add('foo', 'bar');

        $context = $decorator->decorate($context);
        static::assertEquals('bar', $context->get('foo'));
        static::assertEquals('apple', $context->get('baz'));
    }
}
