<?php

namespace Flagception\Tests\FlagceptionBundle\DependencyInjection;

use Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configuration;
use Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator\ArrayConfigurator;
use Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator\ConfigConfigurator;
use Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator\ConstraintConfigurator;
use Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator\ContentfulConfigurator;
use Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator\CookieConfigurator;
use Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator\EnvironmentConfigurator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Class ConfigurationTest
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Tests\FlagceptionBundle\DependencyInjection
 */
class ConfigurationTest extends TestCase
{
    /**
     * Test builder (only if a tree returned)
     *
     * @return void
     */
    public function testBuilder()
    {
        static::assertInstanceOf(TreeBuilder::class, (new Configuration([
            new ArrayConfigurator(),
            new ConstraintConfigurator(),
            new ContentfulConfigurator(),
            new CookieConfigurator(),
            new EnvironmentConfigurator()
        ]))->getConfigTreeBuilder());
    }
}
