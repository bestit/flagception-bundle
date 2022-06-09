<?php

namespace Flagception\Bundle\FlagceptionBundle\Listener;

use Flagception\Bundle\FlagceptionBundle\Annotations\Feature;
use Flagception\Manager\FeatureManagerInterface;
use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class AnnotationSubscriber
 *
 * @author Michel Chowanski <michel.chowanski@bestit-online.de>
 * @package Flagception\Bundle\FlagceptionBundle\Listener
 */
class AnnotationSubscriber implements EventSubscriberInterface
{
    /**
     * Annotation reader
     *
     * @var Reader
     */
    private $reader;

    /**
     * The feature manager
     *
     * @var FeatureManagerInterface
     */
    private $manager;

    /**
     * FeatureListener constructor.
     *
     * @param Reader $reader
     * @param FeatureManagerInterface $manager
     */
    public function __construct(Reader $reader, FeatureManagerInterface $manager)
    {
        $this->reader = $reader;
        $this->manager = $manager;
    }

    /**
     * Filter on controller / method
     *
     * @param ControllerEvent $event
     *
     * @return void
     *
     * @throws NotFoundHttpException
     * @throws ReflectionException
     */
    public function onKernelController(ControllerEvent $event)
    {
        $eventController = $event->getController();

        $controller =  is_array($eventController) === false && method_exists($eventController, '__invoke')
            ? [$eventController, '__invoke']
            : $eventController;

        /*
         * $controller passed can be either a class or a Closure.
         * This is not usual in Symfony2 but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        $object = new ReflectionClass($controller[0]);

        // check for php8 attributes on the controller class
        if (method_exists($object, 'getAttributes')) {
            foreach ($object->getAttributes(Feature::class) as $attribute) {
                $name = $attribute->getArguments()['name'];
                if (!$this->manager->isActive($name)) {
                    throw new NotFoundHttpException(sprintf('Feature %s for class %s is not active.', $name, $object->getName()));
                }
            }
        }


        foreach ($this->reader->getClassAnnotations($object) as $annotation) {
            if ($annotation instanceof Feature) {
                if (!$this->manager->isActive($annotation->name)) {
                    throw new NotFoundHttpException('Feature for this class is not active.');
                }
            }
        }

        $method = $object->getMethod($controller[1]);

        // check for php8 attributes on the method
        if (method_exists($method, 'getAttributes')) {
            foreach ($method->getAttributes(Feature::class) as $attribute) {
                $name = $attribute->getArguments()['name'];
                if (!$this->manager->isActive($name)) {
                    throw new NotFoundHttpException(sprintf('Feature %s for method %s is not active.', $name, $method->getName()));
                }
            }
        }

        foreach ($this->reader->getMethodAnnotations($method) as $annotation) {
            if ($annotation instanceof Feature) {
                if (!$this->manager->isActive($annotation->name)) {
                    throw new NotFoundHttpException('Feature for this method is not active.');
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
