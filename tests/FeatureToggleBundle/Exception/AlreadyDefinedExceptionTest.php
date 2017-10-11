<?php

namespace Tests\BestIt\FeatureToggleBundle\Exception;

use BestIt\FeatureToggleBundle\Exception\AlreadyDefinedException;
use BestIt\FeatureToggleBundle\Exception\FeatureToggleException;
use PHPUnit\Framework\TestCase;

/**
 * Class AlreadyDefinedExceptionTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Tests\BestIt\FeatureToggleBundle\Exception
 */
class AlreadyDefinedExceptionTest extends TestCase
{
    /**
     * Test extends from base exception
     *
     * @return void
     */
    public function testExtends()
    {
        static::assertInstanceOf(FeatureToggleException::class, new AlreadyDefinedException());
    }
}
