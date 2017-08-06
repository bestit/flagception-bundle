<?php

namespace Tests\BestIt\FeatureToggleBundle\Twig;

use BestIt\FeatureToggleBundle\Manager\FeatureManagerInterface;
use BestIt\FeatureToggleBundle\Twig\ToggleExtension;
use PHPUnit\Framework\TestCase;

/**
 * Class ToggleExtensionTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Tests\BestIt\FeatureToggleBundle\Twig
 */
class ToggleExtensionTest extends TestCase
{
    /**
     * Test functions
     *
     * @return void
     */
    public function testFunctions()
    {
        $extension = new ToggleExtension($this->createMock(FeatureManagerInterface::class));

        static::assertEquals('feature', $extension->getFunctions()[0]->getName());
        static::assertEquals('active feature', $extension->getTests()[0]->getName());
    }

    /**
     * Test is active
     *
     * @return void
     */
    public function testIsActive()
    {
        $extension = new ToggleExtension($manager = $this->createMock(FeatureManagerInterface::class));

        $manager
            ->method('isActive')
            ->with('feature_foo')
            ->willReturn(true);

        static::assertEquals(true, $extension->isActive('feature_foo'));
    }

    /**
     * Test get name
     *
     * @return void
     */
    public function testGetName()
    {
        $extension = new ToggleExtension($this->createMock(FeatureManagerInterface::class));
        static::assertEquals('best_it_feature_toggle.twig.toggle_extension', $extension->getName());
    }
}
