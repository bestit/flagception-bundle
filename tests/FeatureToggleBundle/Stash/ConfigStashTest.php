<?php

namespace Tests\BestIt\FeatureToggleBundle\Stash;

use BestIt\FeatureToggleBundle\Model\Context;
use BestIt\FeatureToggleBundle\Stash\ConfigStash;
use BestIt\FeatureToggleBundle\Stash\StashInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

/**
 * Class ConfigStashTest
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package Tests\BestIt\FeatureToggleBundle\Stash
 */
class ConfigStashTest extends TestCase
{
    /**
     * Test implement interface
     *
     * @return void
     */
    public function testImplementInterface()
    {
        $stash = new ConfigStash(new ExpressionLanguage());
        static::assertInstanceOf(StashInterface::class, $stash);
    }

    /**
     * Test name
     *
     * @return void
     */
    public function testName()
    {
        $stash = new ConfigStash(new ExpressionLanguage());
        static::assertEquals('config', $stash->getName());
    }

    /**
     * Test is active
     *
     * @return void
     */
    public function testIsActive()
    {
        $stash = new ConfigStash(new ExpressionLanguage());
        $stash->add('feature_1', true, []);
        $stash->add('feature_2', false, []);
        $stash->add('feature_3', false, []);
        $stash->add('feature_4', true, []);
        $stash->add('feature_5', false, []);

        static::assertFalse($stash->isActive('feature_3', new Context()));
        static::assertTrue($stash->isActive('feature_4', new Context()));
    }

    /**
     * Test unknown feature return false
     *
     * @return void
     */
    public function testUnknownFeature()
    {
        $stash = new ConfigStash(new ExpressionLanguage());
        $stash->add('feature_1', true, []);
        $stash->add('feature_2', false, []);

        static::assertFalse($stash->isActive('feature_3', new Context()));
    }

    /**
     * Test is active by constraint array
     *
     * @return void
     */
    public function testIsActiveByConstraintArray()
    {
        $stash = new ConfigStash(new ExpressionLanguage());
        $stash->add('feature_1', false, ['"ROLE_ADMIN" in context.get("user_role", [])']);

        static::assertFalse($stash->isActive('feature_1', new Context()));

        $context = new Context();
        $context->add('user_role', ['ROLE_USER', 'ROLE_PUBLISHER', 'ROLE_ADMIN']);
        static::assertTrue($stash->isActive('feature_1', $context));
    }

    /**
     * Test is active by constraint int
     *
     * @return void
     */
    public function testIsActiveByConstraintInt()
    {
        $stash = new ConfigStash(new ExpressionLanguage());
        $stash->add('feature_1', false, ['context.get("user_id") === 12']);

        static::assertFalse($stash->isActive('feature_1', new Context()));

        $context = new Context();
        $context->add('user_id', 12);
        static::assertTrue($stash->isActive('feature_1', $context));
    }
}
