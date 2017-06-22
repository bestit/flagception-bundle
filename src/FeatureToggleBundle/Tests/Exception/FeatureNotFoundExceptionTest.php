<?php

namespace BestIt\FeatureToggleBundle\Tests\Exception;

use BestIt\FeatureToggleBundle\Exception\FeatureNotFoundException;
use BestIt\FeatureToggleBundle\Exception\FeatureToggleException;
use PHPUnit\Framework\TestCase;

/**
 * Class FeatureNotFoundExceptionTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Tests\Exception
 */
class FeatureNotFoundExceptionTest extends TestCase
{
    /**
     * Test extends from base exception
     *
     * @return void
     */
    public function testExtends()
    {
        static::assertInstanceOf(FeatureToggleException::class, new FeatureNotFoundException());
    }
}
