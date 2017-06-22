<?php

namespace BestIt\FeatureToggleBundle\Tests\Exception;

use BestIt\FeatureToggleBundle\Exception\FeatureToggleException;
use BestIt\FeatureToggleBundle\Exception\StashNotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * Class StashNotFoundExceptionTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Tests\Exception
 */
class StashNotFoundExceptionTest extends TestCase
{
    /**
     * Test extends from base exception
     *
     * @return void
     */
    public function testExtends()
    {
        static::assertInstanceOf(FeatureToggleException::class, new StashNotFoundException());
    }
}
