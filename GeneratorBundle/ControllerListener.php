<?php

namespace Admingenerator\GeneratorBundle;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;


class ControllerListener
{
    protected $generator;

    public function __construct($generator)
    {
        $this->generator = $generator;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {
            $this->generator->setController($event->getController());
            $this->generator->build();
        }
    }
}
