<?php

namespace Tests\BestIt\FeatureToggleBundle\Bag;

use BestIt\FeatureToggleBundle\Bag\ContextDecoratorBag;
use BestIt\FeatureToggleBundle\Decorator\ArrayContextDecorator;
use BestIt\FeatureToggleBundle\Exception\ContextDecoratorNotFoundException;
use PHPUnit\Framework\TestCase;
use Traversable;

/**
 * Test class for ContextDecoratorBag
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Tests\BestIt\FeatureToggleBundle\Bag
 */
class ContextDecoratorBagTest extends TestCase
{
    /**
     * Test add function
     *
     * @return void
     */
    public function testAdd()
    {
        $bag = new ContextDecoratorBag();
        $bag->add($decorator = new ArrayContextDecorator());

        static::assertSame($decorator, $bag->get('array'));
    }

    /**
     * Test has function
     *
     * @return void
     */
    public function testHas()
    {
        $bag = new ContextDecoratorBag();
        $bag->add($decorator = new ArrayContextDecorator());

        static::assertEquals(true, $bag->has('array'));
        static::assertEquals(false, $bag->has('foobar'));
    }

    /**
     * Test get function
     *
     * @return void
     */
    public function testGet()
    {
        $bag = new ContextDecoratorBag();
        $bag->add($decorator = new ArrayContextDecorator());

        static::assertSame($decorator, $bag->get('array'));
    }

    /**
     * Test get function
     *
     * @return void
     */
    public function testGetMissingFeature()
    {
        $this->expectException(ContextDecoratorNotFoundException::class);

        $bag = new ContextDecoratorBag();
        $bag->add($decorator = new ArrayContextDecorator());

        $bag->get('custom_object');
    }

    /**
     * Test iteration and ordering
     *
     * @return void
     */
    public function testIteration()
    {
        $bag = new ContextDecoratorBag();
        $bag->add($firstDecorator = new ArrayContextDecorator());
        $bag->add($secondDecorator = new ArrayContextDecorator());
        $bag->add($thirdDecorator = new ArrayContextDecorator());

        static::assertInstanceOf(Traversable::class, $bag);

        $i = 0;
        foreach ($bag as $order => $decorator) {
            switch ($i) {
                case 0:
                    static::assertSame($firstDecorator, $decorator);
                    break;

                case 1:
                    static::assertSame($secondDecorator, $decorator);
                    break;

                case 2:
                    static::assertSame($thirdDecorator, $decorator);
                    break;
            }

            $i++;
        }
    }

    /**
     * Test get all
     *
     * @return void
     */
    public function testAll()
    {
        $bag = new ContextDecoratorBag();
        $bag->add($firstDecorator = new ArrayContextDecorator());
        $bag->add($secondDecorator = new ArrayContextDecorator());
        $bag->add($thirdDecorator = new ArrayContextDecorator());

        static::assertInstanceOf(Traversable::class, $bag);

        $i = 0;
        foreach ($bag->all() as $order => $decorator) {
            switch ($i) {
                case 0:
                    static::assertSame($firstDecorator, $decorator);
                    break;

                case 1:
                    static::assertSame($secondDecorator, $decorator);
                    break;

                case 2:
                    static::assertSame($thirdDecorator, $decorator);
                    break;
            }

            $i++;
        }
    }
}
