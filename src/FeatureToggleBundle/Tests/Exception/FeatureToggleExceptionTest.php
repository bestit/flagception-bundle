<?php

namespace BestIt\FeatureToggleBundle\Tests\Exception;

use BestIt\FeatureToggleBundle\Exception\FeatureToggleException;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Class FeatureToggleExceptionTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Tests\Exception
 */
class FeatureToggleExceptionTest extends TestCase
{
    /**
     * Test extends from base exception
     *
     * @return void
     */
    public function testExtends()
    {
        static::assertInstanceOf(Exception::class, new FeatureToggleException());
    }
}
