<?php

namespace Tests\BestIt\FeatureToggleBundle\Exception;

use BestIt\FeatureToggleBundle\Exception\FeatureToggleException;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Class FeatureToggleExceptionTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Tests\BestIt\FeatureToggleBundle\Exception
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
