<?php

namespace Flagception\Tests\FlagceptionBundle\DependencyInjection\Configurator;

use Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator\ArrayConfigurator;
use Flagception\Bundle\FlagceptionBundle\DependencyInjection\FlagceptionExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class ArrayConfiguratorTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Flagception\Tests\FlagceptionBundle\DependencyInjection\Configurator
 */
class ArrayConfiguratorTest extends TestCase
{
    /**
     * The container
     *
     * @var ContainerBuilder
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $container = new ContainerBuilder();
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../../../src/Resources/config'));
        $loader->load('configurators.yml');

        $this->container = $container;
    }

    /**
     * Test key
     *
     * @return void
     */
    public function testKey()
    {
        static::assertEquals('array', (new ArrayConfigurator())->getKey());
    }

    /**
     * Test activator default state
     *
     * @return void
     */
    public function testActivatorDefaultState()
    {
        $config = [];
        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        static::assertTrue($this->container->hasDefinition('flagception.activator.array_activator'));
    }

    /**
     * Test activator default state
     *
     * @return void
     */
    public function testActivatorDefaultPriority()
    {
        $config = [
            [
                'activators' => [
                    'array' => [
                        'enable' => true
                    ]
                ]
            ]
        ];
        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        $definition = $this->container->getDefinition('flagception.activator.array_activator');
        static::assertEquals(255, $definition->getTag('flagception.activator')[0]['priority']);
    }

    /**
     * Test activator can be disabled
     *
     * @return void
     */
    public function testActivatorCanByDisabled()
    {
        $config = [
            [
                'activators' => [
                    'array' => [
                        'enable' => false
                    ]
                ]
            ]
        ];
        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        static::assertFalse($this->container->hasDefinition('flagception.activator.array_activator'));
    }

    /**
     * Test activator can be disabled by string
     *
     * @return void
     */
    public function testActivatorCanByDisabledByString()
    {
        $config = [
            [
                'activators' => [
                    'array' => [
                        'enable' => 'false'
                    ]
                ]
            ]
        ];
        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        static::assertFalse($this->container->hasDefinition('flagception.activator.array_activator'));
    }

    /**
     * Test set activator priority
     *
     * @return void
     */
    public function testActivatorSetPriority()
    {
        $config = [
            [
                'activators' => [
                    'array' => [
                        'enable' => true,
                        'priority' => 10
                    ]
                ]
            ]
        ];
        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        $definition = $this->container->getDefinition('flagception.activator.array_activator');
        static::assertEquals(10, $definition->getTag('flagception.activator')[0]['priority']);
    }

    /**
     * Test add features
     *
     * @return void
     */
    public function testAddFeatures()
    {
        $config = [
            [
                'features' => [
                    'feature_foo' => [
                        'default' => true
                    ],
                    'feature_bar' => [
                        'default' => false
                    ],
                    'feature_bazz' => [
                        'default' => 'true'
                    ]
                ]
            ]
        ];

        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        static::assertEquals(
            [
                'feature_foo' => true,
                'feature_bar' => false,
                'feature_bazz' => 'true'
            ],
            $this->container->getDefinition('flagception.activator.array_activator')->getArgument(0)
        );
    }
}
