<?php

namespace Flagception\Tests\FlagceptionBundle\Fixtures\Helper;

use Flagception\Bundle\FlagceptionBundle\Annotations\Feature;

/**
 * Class AnnotationTestClass
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Tests\FlagceptionBundle\Fixtures\Helper
 *
 * @Feature("feature_abc")
 */
class AnnotationTestClass
{
    public function normalMethod()
    {
    }

    public function validMethod()
    {
    }

    /**
     * @Feature("feature_def")
     */
    public function invalidMethod()
    {
    }
}
