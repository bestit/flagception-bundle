<?php

namespace BestIt\FeatureToggleBundle\Twig;

use BestIt\FeatureToggleBundle\Manager\FeatureManagerInterface;
use BestIt\FeatureToggleBundle\Model\Context;
use Twig_Extension;
use Twig_SimpleFunction;
use Twig_SimpleTest;

/**
 * Class ToggleExtension
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Twig
 */
class ToggleExtension extends Twig_Extension
{
    /**
     * The manager
     *
     * @var FeatureManagerInterface
     */
    private $manager;

    /**
     * ToggleExtension constructor.
     *
     * @param FeatureManagerInterface $manager
     */
    public function __construct(FeatureManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Is feature name active
     *
     * @param string $name
     * @param array $contextValues
     *
     * @return bool
     */
    public function isActive($name, array $contextValues = []): bool
    {
        $context = new Context();
        foreach ($contextValues as $contextKey => $contextValue) {
            $context->add($contextKey, $contextValue);
        }

        return $this->manager->isActive($name, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('feature', [$this, 'isActive']),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getTests()
    {
        return [
            new Twig_SimpleTest('active feature', [$this, 'isActive']),
        ];
    }

    /**
     * Returns the name
     * (needed for supporting twig <1.26)
     *
     * @return string
     */
    public function getName(): string
    {
        return 'best_it_feature_toggle.twig.toggle_extension';
    }
}
