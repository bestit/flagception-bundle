<?php

namespace Flagception\Tests\FlagceptionBundle\DependencyInjection\Configurator;

use Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator\CookieConfigurator;
use Flagception\Bundle\FlagceptionBundle\DependencyInjection\FlagceptionExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class CookieConfiguratorTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Flagception\Tests\FlagceptionBundle\DependencyInjection\Configurator
 */
class CookieConfiguratorTest extends TestCase
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
    protected function setUp()
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
        static::assertEquals('cookie', (new CookieConfigurator())->getKey());
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

        static::assertFalse($this->container->hasDefinition('flagception.activator.cookie_activator'));
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
                    'cookie' => [
                        'enable' => true
                    ]
                ]
            ]
        ];
        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        $definition = $this->container->getDefinition('flagception.activator.cookie_activator');
        static::assertEquals(200, $definition->getTag('flagception.activator')[0]['priority']);
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
                    'cookie' => [
                        'enable' => false
                    ]
                ]
            ]
        ];
        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        static::assertFalse($this->container->hasDefinition('flagception.activator.cookie_activator'));
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
                    'cookie' => [
                        'enable' => 'false'
                    ]
                ]
            ]
        ];
        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        static::assertFalse($this->container->hasDefinition('flagception.activator.cookie_activator'));
    }

    /**
     * Test activator can be enabled
     *
     * @return void
     */
    public function testActivatorCanByEnabled()
    {
        $config = [
            [
                'activators' => [
                    'cookie' => [
                        'enable' => true
                    ]
                ]
            ]
        ];
        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        static::assertTrue($this->container->hasDefinition('flagception.activator.cookie_activator'));
    }

    /**
     * Test activator can be enabled by string
     *
     * @return void
     */
    public function testActivatorCanByEnabledByString()
    {
        $config = [
            [
                'activators' => [
                    'cookie' => [
                        'enable' => 'true'
                    ]
                ]
            ]
        ];
        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        static::assertTrue($this->container->hasDefinition('flagception.activator.cookie_activator'));
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
                    'cookie' => [
                        'enable' => true,
                        'priority' => 10
                    ]
                ]
            ]
        ];
        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        $definition = $this->container->getDefinition('flagception.activator.cookie_activator');
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
                        'cookie' => $cookie1 = true
                    ],
                    'feature_bar' => [
                        'cookie' => false
                    ],
                    'feature_bazz' => [
                        'cookie' => $cookie2 = 'true'
                    ],
                    'feature_foobazz' => []
                ],
                'activators' => [
                    'cookie' => [
                        'enable' => true
                    ]
                ]
            ]
        ];

        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        static::assertEquals(
            [
                [
                    'feature_foo',
                    'feature_bazz',
                    'feature_foobazz'
                ],
                'flagception',
                ','
            ],
            $this->container->getDefinition('flagception.activator.cookie_activator')->getArguments()
        );
    }

    /**
     * Test full config
     *
     * @return void
     */
    public function testFullConfig()
    {
        $config = [
            [
                'activators' => [
                    'cookie' => [
                        'enable' => true,
                        'name' => $name = uniqid(),
                        'separator' => $separator = uniqid()
                    ]
                ]
            ]
        ];

        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        static::assertEquals(
            [
                [],
                $name,
                $separator
            ],
            $this->container->getDefinition('flagception.activator.cookie_activator')->getArguments()
        );
    }
}
