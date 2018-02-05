<?php

namespace Flagception\Tests\FlagceptionBundle\DependencyInjection\Configurator;

use Flagception\Bundle\FlagceptionBundle\DependencyInjection\Configurator\ConstraintConfigurator;
use Flagception\Bundle\FlagceptionBundle\DependencyInjection\FlagceptionExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ConstraintConfiguratorTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Flagception\Tests\FlagceptionBundle\DependencyInjection\Configurator
 */
class ConstraintConfiguratorTest extends TestCase
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
        static::assertEquals('constraint', (new ConstraintConfigurator())->getKey());
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

        static::assertTrue($this->container->hasDefinition('flagception.activator.constraint_activator'));
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
                    'constraint' => [
                        'enable' => true
                    ]
                ]
            ]
        ];
        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        $definition = $this->container->getDefinition('flagception.activator.constraint_activator');
        static::assertEquals(210, $definition->getTag('flagception.activator')[0]['priority']);
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
                    'constraint' => [
                        'enable' => false
                    ]
                ]
            ]
        ];
        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        static::assertFalse($this->container->hasDefinition('flagception.activator.constraint_activator'));
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
                    'constraint' => [
                        'enable' => 'false'
                    ]
                ]
            ]
        ];
        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        static::assertFalse($this->container->hasDefinition('flagception.activator.constraint_activator'));
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
                    'constraint' => [
                        'enable' => true,
                        'priority' => 10
                    ]
                ]
            ]
        ];
        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        $definition = $this->container->getDefinition('flagception.activator.constraint_activator');
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
                        'constraint' => 'false == true'
                    ],
                    'feature_bar' => [
                        'constraint' => false
                    ],
                    'feature_bazz' => [
                        'constraint' => 'true == false'
                    ],
                    'feature_foobazz' => []
                ]
            ]
        ];

        $extension = new FlagceptionExtension();
        $extension->load($config, $this->container);

        static::assertEquals(
            new Reference('flagception.constraint.constraint_resolver'),
            $this->container->getDefinition('flagception.activator.constraint_activator')->getArgument(0)
        );

        static::assertEquals(
            [
                'feature_foo' => 'false == true',
                'feature_bazz' => 'true == false'
            ],
            $this->container->getDefinition('flagception.activator.constraint_activator')->getArgument(1)
        );
    }
}
