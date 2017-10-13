<?php

namespace Tests\BestIt\FeatureToggleBundle\Bag;

use BestIt\FeatureToggleBundle\Bag\StashBag;
use BestIt\FeatureToggleBundle\Exception\StashNotFoundException;
use BestIt\FeatureToggleBundle\Stash\ConfigStash;
use BestIt\FeatureToggleBundle\Stash\CookieStash;
use BestIt\FeatureToggleBundle\Stash\ArrayStash;
use PHPUnit\Framework\TestCase;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\RequestStack;
use Traversable;

/**
 * Class StashBagTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Tests\BestIt\FeatureToggleBundle\Bag
 */
class StashBagTest extends TestCase
{
    /**
     * Test add function
     *
     * @return void
     */
    public function testAdd()
    {
        $bag = new StashBag();
        $bag->add($arrayStash = new ArrayStash());
        $bag->add($configStash = new ConfigStash(new ExpressionLanguage()));

        static::assertSame($configStash, $bag->get('config'));
    }

    /**
     * Test has function
     *
     * @return void
     */
    public function testHas()
    {
        $bag = new StashBag();
        $bag->add($arrayStash = new ArrayStash());
        $bag->add($configStash = new ConfigStash(new ExpressionLanguage()));

        static::assertEquals(true, $bag->has('config'));
        static::assertEquals(false, $bag->has('foobar'));
    }

    /**
     * Test get function
     *
     * @return void
     */
    public function testGet()
    {
        $bag = new StashBag();
        $bag->add($arrayStash = new ArrayStash());
        $bag->add($configStash = new ConfigStash(new ExpressionLanguage()));

        static::assertSame($configStash, $bag->get('config'));
    }

    /**
     * Test get function
     *
     * @return void
     */
    public function testGetMissingFeature()
    {
        $this->expectException(StashNotFoundException::class);

        $bag = new StashBag();
        $bag->add($arrayStash = new ArrayStash());
        $bag->add($configStash = new ConfigStash(new ExpressionLanguage()));

        $bag->get('custom_object');
    }

    /**
     * Test iteration and ordering
     *
     * @return void
     */
    public function testIteration()
    {
        $bag = new StashBag();
        $bag->add($arrayStash = new ArrayStash());
        $bag->add($configStash = new ConfigStash(new ExpressionLanguage()));
        $bag->add($cookieStash = new CookieStash(new RequestStack(), 'cookie', ','));

        static::assertInstanceOf(Traversable::class, $bag);

        $i = 0;
        foreach ($bag as $order => $stash) {
            switch ($i) {
                case 0:
                    static::assertSame($arrayStash, $stash);
                    break;

                case 1:
                    static::assertSame($configStash, $stash);
                    break;

                case 2:
                    static::assertSame($cookieStash, $stash);
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
        $bag = new StashBag();
        $bag->add($arrayStash = new ArrayStash());
        $bag->add($configStash = new ConfigStash(new ExpressionLanguage()));
        $bag->add($cookieStash = new CookieStash(new RequestStack(), 'cookie', ','));

        static::assertInstanceOf(Traversable::class, $bag);

        $i = 0;
        foreach ($bag->all() as $order => $stash) {
            switch ($i) {
                case 0:
                    static::assertSame($arrayStash, $stash);
                    break;

                case 1:
                    static::assertSame($configStash, $stash);
                    break;

                case 2:
                    static::assertSame($cookieStash, $stash);
                    break;
            }

            $i++;
        }
    }
}
