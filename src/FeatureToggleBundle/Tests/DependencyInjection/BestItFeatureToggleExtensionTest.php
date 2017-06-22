<?php

namespace BestIt\FeatureToggleBundle\Tests\DependencyInjection;

use BestIt\FeatureToggleBundle\DependencyInjection\BestItFeatureToggleExtension;
use BestIt\FeatureToggleBundle\Stash\CookieStash;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class BestItFeatureToggleExtensionTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Tests\DependencyInjection
 */
class BestItFeatureToggleExtensionTest extends TestCase
{
    /**
     * Test extends from base exception
     *
     * @return void
     */
    public function testAddFeatures()
    {
        $container = new ContainerBuilder();
        $config = [
            [
                'features' => [
                    'feature_foo' => [
                        'active' => true
                    ],
                    'feature_bar' => [
                        'active' => false
                    ]
                ]
            ]
        ];

        $extension = new BestItFeatureToggleExtension();
        $extension->load($config, $container);

        static::assertEquals([
            [
                'add',
                [
                    'feature_foo',
                    true
                ]
            ],
            [
                'add',
                [
                    'feature_bar',
                    false
                ]
            ]
        ], $container->getDefinition('best_it_feature_toggle.bag.feature_bag')->getMethodCalls());
    }

    /**
     * Test that cookie stash is disabled
     *
     * @return void
     */
    public function testCookieStashDisabled()
    {
        $container = new ContainerBuilder();
        $config = [
            [
                'features' => [
                    'feature_foo' => [
                        'active' => true
                    ],
                    'feature_bar' => [
                        'active' => false
                    ]
                ]
            ]
        ];

        $extension = new BestItFeatureToggleExtension();
        $extension->load($config, $container);

        static::assertFalse($container->hasDefinition('best_it_feature_toggle.stash.cookie_stash'));
    }

    /**
     * Test that cookie stash is enabled
     *
     * @return void
     */
    public function testCookieStashEnabled()
    {
        $container = new ContainerBuilder();
        $config = [
            [
                'features' => [
                    'feature_foo' => [
                        'active' => true
                    ],
                    'feature_bar' => [
                        'active' => false
                    ]
                ],
                'cookie_stash' => [
                    'active' => true,
                    'name' => 'foo-cookie'
                ]
            ]
        ];

        $extension = new BestItFeatureToggleExtension();
        $extension->load($config, $container);

        $definition = $container->getDefinition('best_it_feature_toggle.stash.cookie_stash');

        static::assertEquals(CookieStash::class, $definition->getClass());
        static::assertEquals([new Reference('request_stack'), 'foo-cookie'], $definition->getArguments());
        static::assertEquals(['best_it_feature_toggle.stash' => [['priority' => 255]]], $definition->getTags());
    }
}
