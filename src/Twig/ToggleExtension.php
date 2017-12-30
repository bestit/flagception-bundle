<?php

namespace Flagception\Bundle\FlagceptionBundle\Twig;

use Flagception\Manager\FeatureManagerInterface;
use Flagception\Model\Context;
use Twig_Extension;
use Twig_SimpleFunction;
use Twig_SimpleTest;

/**
 * Class ToggleExtension
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\Twig
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
    public function isActive($name, array $contextValues = [])
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
    public function getName()
    {
        return 'flagception';
    }
}
