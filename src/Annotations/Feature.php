<?php

namespace Flagception\Bundle\FlagceptionBundle\Annotations;
use \Attribute;
/**
 * Class Feature
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\Annotations
 * @Annotation
 */

#[Attribute(Attribute::TARGET_ALL)]
class Feature
{
    /**
     * Name of feature
     *
     * @var string
     */
    public $name;

    public function __construct($name) {
        if (is_string($name)) {
            $this->name = $name;
        } else {
            $this->name = $name['value'];
        }
    }

    public function getName()
    {
        return $this->name;
    }
}
