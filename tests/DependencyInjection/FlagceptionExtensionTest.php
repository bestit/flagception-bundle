<?php

namespace Flagception\Tests\FlagceptionBundle\DependencyInjection;

use Flagception\Bundle\FlagceptionBundle\DependencyInjection\FlagceptionExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class FlagceptionExtensionTest
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Tests\FlagceptionBundle\DependencyInjection
 */
class FlagceptionExtensionTest extends TestCase
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
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../../src/Resources/config'));
        $loader->load('configurators.yml');

        $this->container = $container;
    }

    /**
     * Test that annotation subscriber is disabled
     *
     * @return void
     */
    public function testAnnotationSubscriberDisabled()
    {
        $config = [
            [
                'features' => [
                    'feature_foo' => [
                        'default' => true
                    ],
                    'feature_bar' => [
                        'default' => false
                    ]
                ]
            ]
        ];

        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        static::assertFalse($this->container->hasDefinition('flagception.listener.annotation_subscriber'));
    }

    /**
     * Test that annotation subscriber is enabled
     *
     * @return void
     */
    public function testAnnotationSubscriberEnabled()
    {
        $config = [
            [
                'annotation' => [
                    'enable' => true
                ],
                'features' => [
                    'feature_foo' => [
                        'default' => true
                    ],
                    'feature_bar' => [
                        'default' => false
                    ]
                ]
            ]
        ];

        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        $definition = $this->container->getDefinition('flagception.listener.annotation_subscriber');
        static::assertTrue($definition->hasTag('kernel.event_subscriber'));
    }

    /**
     * Test that annotation subscriber is enabled by string
     *
     * @return void
     */
    public function testAnnotationSubscriberEnabledByString()
    {
        $config = [
            [
                'annotation' => [
                    'enable' => 'true'
                ],
                'features' => [
                    'feature_foo' => [
                        'default' => true
                    ],
                    'feature_bar' => [
                        'default' => false
                    ]
                ]
            ]
        ];

        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        $definition = $this->container->getDefinition('flagception.listener.annotation_subscriber');
        static::assertTrue($definition->hasTag('kernel.event_subscriber'));
    }

    /**
     * Test that routing metadata subscriber is disabled
     *
     * @return void
     */
    public function testRoutingMetadataSubscriberDisabled()
    {
        $config = [
            [
                'routing_metadata' => [
                    'enable' => false
                ],
                'features' => [
                    'feature_foo' => [
                        'default' => true
                    ],
                    'feature_bar' => [
                        'default' => false
                    ]
                ]
            ]
        ];

        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        static::assertFalse($this->container->hasDefinition('flagception.listener.routing_metadata_subscriber'));
    }

    /**
     * Test that routing metadata subscriber is enabled
     *
     * @return void
     */
    public function testRoutingMetadataSubscriberEnabled()
    {
        $config = [
            [
                'features' => [
                    'feature_foo' => [
                        'default' => true
                    ],
                    'feature_bar' => [
                        'default' => false
                    ]
                ]
            ]
        ];

        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        $definition = $this->container->getDefinition('flagception.listener.routing_metadata_subscriber');
        static::assertTrue($definition->hasTag('kernel.event_subscriber'));
    }

    /**
     * Test that routing metadata subscriber is enabled by string
     *
     * @return void
     */
    public function testRoutingMetadataSubscriberEnabledByString()
    {
        $config = [
            [
                'routing_metadata' => [
                    'enable' => 'true'
                ],
                'features' => [
                    'feature_foo' => [
                        'default' => true
                    ],
                    'feature_bar' => [
                        'default' => false
                    ]
                ]
            ]
        ];

        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        $definition = $this->container->getDefinition('flagception.listener.routing_metadata_subscriber');
        static::assertTrue($definition->hasTag('kernel.event_subscriber'));
    }
}
