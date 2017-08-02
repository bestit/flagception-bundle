<?php

namespace BestIt\FeatureToggleBundle\Twig;

use BestIt\FeatureToggleBundle\Manager\FeatureManagerInterface;
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
     *
     * @return boolean
     */
    public function isActive($name): bool
    {
        return $this->manager->isActive($name);
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
