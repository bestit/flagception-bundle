<?php

namespace Flagception\Tests\FlagceptionBundle\Profiler;

use Flagception\Activator\ArrayActivator;
use Flagception\Activator\CookieActivator;
use Flagception\Bundle\FlagceptionBundle\Activator\TraceableChainActivator;
use Flagception\Decorator\ArrayDecorator;
use Flagception\Decorator\ChainDecorator;
use Flagception\Model\Context;
use Flagception\Bundle\FlagceptionBundle\Profiler\FeatureDataCollector;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class FeatureDataCollectorTest
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Tests\FlagceptionBundle\Profiler
 */
class FeatureDataCollectorTest extends TestCase
{
    /**
     * Test get name
     *
     * @return void
     */
    public function testGetName()
    {
        $collector = new FeatureDataCollector(new TraceableChainActivator(), new ChainDecorator());
        static::assertEquals('flagception', $collector->getName());
    }

    /**
     * Test the reset method
     *
     * @return void
     */
    public function testReset()
    {
        $collector = new FeatureDataCollector(new TraceableChainActivator(), new ChainDecorator());
        $collector->reset();
    }

    /**
     * Test complete collect handling
     *
     * @return void
     */
    public function testCollect()
    {
        $collector = new FeatureDataCollector(
            $chainActivator = $this->createMock(TraceableChainActivator::class),
            $chainDecorator = new ChainDecorator()
        );

        $chainDecorator->add(new ArrayDecorator());

        $chainActivator
            ->expects(static::once())
            ->method('getActivators')
            ->willReturn([new ArrayActivator(), new CookieActivator([])]);

        $chainActivator
            ->expects(static::exactly(2))
            ->method('getTrace')
            ->willReturn([
                [
                    'feature' => 'abc',
                    'context' => new Context(),
                    'result' => true,
                    'stack' => [
                        'array' => false,
                        'cookie' => true
                    ]
                ],
                [
                    'feature' => 'abc',
                    'context' => new Context(),
                    'result' => true,
                    'stack' => [
                        'array' => false,
                        'cookie' => true
                    ]
                ],
                [
                    'feature' => 'def',
                    'context' => new Context(),
                    'result' => false,
                    'stack' => [
                        'array' => false,
                        'cookie' => false
                    ]
                ],
                [
                    'feature' => 'ywz',
                    'context' => new Context(),
                    'result' => true,
                    'stack' => [
                        'array' => true
                    ]
                ],
                [
                    'feature' => 'corrupt',
                    'context' => new Context(),
                    'result' => true,
                    'stack' => [
                        'array' => false,
                        'cookie' => true
                    ]
                ],
                [
                    'feature' => 'corrupt',
                    'context' => new Context(),
                    'result' => false,
                    'stack' => [
                        'array' => false,
                        'cookie' => false
                    ]
                ]
            ]);

        $collector->collect(new Request(), new Response());

        static::assertEquals([
            'features' => 4,
            'activeFeatures' => 2,
            'inactiveFeatures' => 1,
            'corruptFeatures' => 1
        ], $collector->getSummary());

        static::assertEquals([
            'array' => [
                'priority' => 1,
                'name' => 'array'
            ]
        ], $collector->getDecorators());

        static::assertEquals([
            'array' => [
                'priority' => 1,
                'name' => 'array',
                'requests' => 6,
                'activeRequests' => 1,
                'inactiveRequests' => 5,
            ],
            'cookie' => [
                'priority' => 2,
                'name' => 'cookie',
                'requests' => 5,
                'activeRequests' => 3,
                'inactiveRequests' => 2,
            ]
        ], $collector->getActivators());

        static::assertEquals([
            [
                'feature' => 'abc',
                'context' => new Context(),
                'result' => true,
                'stack' => [
                    'array' => false,
                    'cookie' => true
                ]
            ],
            [
                'feature' => 'abc',
                'context' => new Context(),
                'result' => true,
                'stack' => [
                    'array' => false,
                    'cookie' => true
                ]
            ],
            [
                'feature' => 'def',
                'context' => new Context(),
                'result' => false,
                'stack' => [
                    'array' => false,
                    'cookie' => false
                ]
            ],
            [
                'feature' => 'ywz',
                'context' => new Context(),
                'result' => true,
                'stack' => [
                    'array' => true
                ]
            ],
            [
                'feature' => 'corrupt',
                'context' => new Context(),
                'result' => true,
                'stack' => [
                    'array' => false,
                    'cookie' => true
                ]
            ],
            [
                'feature' => 'corrupt',
                'context' => new Context(),
                'result' => false,
                'stack' => [
                    'array' => false,
                    'cookie' => false
                ]
            ]
        ], $collector->getTrace());

        static::assertEquals([
            'abc' => [
                'requests' => 2,
                'activeRequests' => 2,
                'inactiveRequests' => 0,
                'activators' => [
                    'cookie'
                ]
            ],
            'def' => [
                'requests' => 1,
                'activeRequests' => 0,
                'inactiveRequests' => 1,
                'activators' => []
            ],
            'ywz' => [
                'requests' => 1,
                'activeRequests' => 1,
                'inactiveRequests' => 0,
                'activators' => [
                    'array'
                ]
            ],
            'corrupt' => [
                'requests' => 2,
                'activeRequests' => 1,
                'inactiveRequests' => 1,
                'activators' => [
                    'cookie'
                ]
            ],
        ], $collector->getRequests());
    }
}
