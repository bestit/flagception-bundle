<?php

namespace Flagception\Tests\FlagceptionBundle\DependencyInjection;

use Flagception\Activator\FeatureActivatorInterface;
use Flagception\Bundle\FlagceptionBundle\DependencyInjection\FlagceptionExtension;
use Flagception\Decorator\ContextDecoratorInterface;
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
    protected function setUp(): void
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
        $config = [];

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
        $config = [];

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
                ]
            ]
        ];

        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        $definition = $this->container->getDefinition('flagception.listener.routing_metadata_subscriber');
        static::assertTrue($definition->hasTag('kernel.event_subscriber'));
    }

    /**
     * Test that annotation subscriber is disabled
     *
     * @return void
     */
    public function testAutConfiguration()
    {
        if (method_exists($this->container, 'registerForAutoconfiguration') === false) {
            $this->markTestSkipped('Only since Symfony 3.3');
        }

        $config = [];

        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        $activatorChildDefinition = $this->container->getAutoconfiguredInstanceof()[FeatureActivatorInterface::class];
        static::assertEquals([
            'flagception.activator' => [[]]
        ], $activatorChildDefinition->getTags());

        $contextChildDefinition = $this->container->getAutoconfiguredInstanceof()[ContextDecoratorInterface::class];
        static::assertEquals([
            'flagception.context_decorator' => [[]]
        ], $contextChildDefinition->getTags());
    }
}
