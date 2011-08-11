<?php

namespace Admingenerator\GeneratorBundle\EventListener;

use Admingenerator\GeneratorBundle\Generator\GeneratorInterface;

use Symfony\Component\HttpKernel\HttpKernelInterface;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

use Admingenerator\GeneratorBundle\Exception\NotAdminGeneratedException;

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
        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) { //I don't know why but i 'm on sub request !!
            try {
                $controller = $event->getRequest()->attributes->get('_controller');
                $this->generator->setController($controller);
                $this->generator->build();
            } catch (NotAdminGeneratedException $e) {
                //Lets the word running this is not an admin generated module
            }
        }
    }

}
