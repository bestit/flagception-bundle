<?php

namespace BestIt\FeatureToggleBundle\Stash;

use BestIt\FeatureToggleBundle\Exception\ConstraintSyntaxException;
use BestIt\FeatureToggleBundle\Model\Context;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\SyntaxError;

/**
 * Class ConfigStash
 *
 * @author Michel Chowanski <chowanski@bestit-online.de>
 * @package BestIt\FeatureToggleBundle\Stash
 */
class ConfigStash implements StashInterface
{
    /**
     * The features
     *
     * @var array
     */
    private $features = [];

    /**
     * The expression language parser
     *
     * @var ExpressionLanguage
     */
    private $expressionLanguage;

    /**
     * ConfigStash constructor.
     *
     * @param ExpressionLanguage $expressionLanguage
     */
    public function __construct(ExpressionLanguage $expressionLanguage)
    {
        $this->expressionLanguage = $expressionLanguage;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'config';
    }

    /**
     * @inheritdoc
     */
    public function isActive(string $name, Context $context): bool
    {
        if (!array_key_exists($name, $this->features)) {
            return false;
        }

        // If default already true?
        if ($this->features[$name]['default'] === true) {
            return true;
        }

        // Default is false ... check constraints
        $constraints = $this->features[$name]['constraints'] ?? [];
        foreach ($constraints as $constraint) {
            try {
                $result = $this->expressionLanguage->evaluate($constraint, array_merge(
                    $context->all(),
                    ['context' => $context]
                ));
            } catch (SyntaxError $exception) {
                throw new ConstraintSyntaxException('Feature toggle constraint is invalid', 0, $exception);
            }

            if ($result === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add active feature
     *
     * @param string $feature
     * @param bool $isActive
     * @param array $constraints
     *
     * @return void
     */
    public function add(string $feature, bool $isActive, array $constraints)
    {
        $this->features[$feature] = [
            'default' => $isActive,
            'constraints' => $constraints
        ];
    }
}
