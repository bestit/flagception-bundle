<?php

namespace Tests\BestIt\FeatureToggleBundle\Model;

use BestIt\FeatureToggleBundle\Exception\AlreadyDefinedException;
use BestIt\FeatureToggleBundle\Model\Context;
use PHPUnit\Framework\TestCase;

/**
 * Class ContextTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Tests\BestIt\FeatureToggleBundle\Model
 */
class ContextTest extends TestCase
{
    /**
     * Test storage is empty
     *
     * @return void
     */
    public function testEmptyStorage()
    {
        $context = new Context();
        static::assertEquals([], $context->all());
    }

    /**
     * Test add context value
     *
     * @return void
     */
    public function testAdd()
    {
        $context = new Context();
        $context->add('foo', 'bar');
        $context->add('role', 'ROLE_ADMIN');

        static::assertEquals(['foo' => 'bar', 'role' => 'ROLE_ADMIN'], $context->all());
    }

    /**
     * Test add context value throw exception if already defined
     *
     * @return void
     */
    public function testAddThrowException()
    {
        $this->expectException(AlreadyDefinedException::class);

        $context = new Context();
        $context->add('foo', 'bar');
        $context->add('role', 'ROLE_ADMIN');
        $context->add('foo', 'bar');
    }

    /**
     * Test replace context value
     *
     * @return void
     */
    public function testReplace()
    {
        $context = new Context();
        $context->add('foo', 'bar');
        $context->add('role', 'ROLE_ADMIN');
        $context->replace('foo', 'best-it');

        static::assertEquals(['foo' => 'best-it', 'role' => 'ROLE_ADMIN'], $context->all());
    }

    /**
     * Test get context value return null
     *
     * @return void
     */
    public function testGetReturnNull()
    {
        $context = new Context();
        $context->add('foo', 'bar');
        $context->add('role', 'ROLE_ADMIN');

        static::assertEquals(null, $context->get('bar'));
    }

    /**
     * Test get context value return default value
     *
     * @return void
     */
    public function testGetReturnDefaultValue()
    {
        $context = new Context();
        $context->add('foo', 'bar');
        $context->add('role', 'ROLE_ADMIN');

        static::assertEquals('best-it', $context->get('bar', 'best-it'));
    }

    /**
     * Test get context value return value
     *
     * @return void
     */
    public function testGetReturnValue()
    {
        $context = new Context();
        $context->add('foo', 'bar');
        $context->add('role', 'ROLE_ADMIN');

        static::assertEquals('ROLE_ADMIN', $context->get('role', 'best-it'));
    }

    /**
     * Test all return complete storage
     *
     * @return void
     */
    public function testAll()
    {
        $context = new Context();
        $context->add('foo', 'bar');
        $context->add('role', 'ROLE_ADMIN');

        static::assertEquals(['foo' => 'bar', 'role' => 'ROLE_ADMIN'], $context->all());
    }
}
