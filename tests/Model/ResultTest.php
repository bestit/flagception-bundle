<?php

namespace Flagception\Tests\FlagceptionBundle\Model;

use Flagception\Bundle\FlagceptionBundle\Model\Result;
use Flagception\Model\Context;
use PHPUnit\Framework\TestCase;

/**
 * Class ResultTest
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Tests\FlagceptionBundle\Model
 */
class ResultTest extends TestCase
{
    /**
     * Test all properties
     *
     * @return void
     */
    public function testAllProperties()
    {
        $result = new Result('feature_abc', true, $context = new Context(), 'config');

        static::assertEquals('feature_abc', $result->getFeatureName());
        static::assertEquals(true, $result->isActive());
        static::assertSame($context, $result->getContext());
        static::assertEquals('config', $result->getActivator());
    }
}
