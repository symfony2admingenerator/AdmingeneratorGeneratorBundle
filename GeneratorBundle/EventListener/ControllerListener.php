<?php

namespace Admingenerator\GeneratorBundle\EventListener;

use Admingenerator\GeneratorBundle\Generator\GeneratorInterface;

use Symfony\Component\HttpKernel\HttpKernelInterface;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class ControllerListener
{
    protected $generator;
    
    protected $router;

    public function __construct(GeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {
            $controller = $event->getRequest()->attributes->get('_controller');
            $this->generator->setController($controller);
            $this->generator->build();
        }
    }

}
