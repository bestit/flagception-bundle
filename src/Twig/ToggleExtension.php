<?php

namespace Flagception\Bundle\FlagceptionBundle\Twig;

use Flagception\Manager\FeatureManagerInterface;
use Flagception\Model\Context;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigTest;

/**
 * Class ToggleExtension
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\Twig
 */
class ToggleExtension extends AbstractExtension
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
    public function isActive(string $name, array $contextValues = []): bool
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
    public function getFunctions(): array
    {
        return [
            new TwigFunction('feature', [$this, 'isActive']),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getTests(): array
    {
        return [
            new TwigTest('active feature', [$this, 'isActive']),
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
        return 'flagception';
    }
}
