<?php

namespace Tests\BestIt\FeatureToggleBundle\DependencyInjection;

use BestIt\FeatureToggleBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Class ConfigurationTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Tests\BestIt\FeatureToggleBundle\DependencyInjection
 */
class ConfigurationTest extends TestCase
{
    /**
     * Test extends from base exception
     *
     * @return void
     */
    public function testBuilder()
    {
        static::assertInstanceOf(TreeBuilder::class, (new Configuration())->getConfigTreeBuilder());
    }
}
