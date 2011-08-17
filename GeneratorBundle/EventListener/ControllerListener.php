<?php

namespace Admingenerator\GeneratorBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\Yaml\Yaml;

use Symfony\Component\HttpKernel\HttpKernelInterface;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

use Admingenerator\GeneratorBundle\Exception\NotAdminGeneratedException;
use Symfony\Component\Finder\Finder;

class ControllerListener
{
    protected $container;
    
    protected $router;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) { //I don't know why but i 'm on sub request !!
            try {
                $controller = $event->getRequest()->attributes->get('_controller');

                if(strstr($controller, '::')) { //Check if its a "real controller" not assetic for example
                    $generatorYaml = $this->getGeneratorYml($controller);
                    
                    $generator = $this->getGenerator($generatorYaml);
                    $generator->setGeneratorYml($generatorYaml);
                    $generator->build();
                }
            } catch (NotAdminGeneratedException $e) {
                //Lets the word running this is not an admin generated module
            }
        }
    }
    
    protected function getGenerator($generatorYaml)
    {
        $yaml = Yaml::parse($generatorYaml);
        
        return $this->container->get($yaml['generator']);
    }
    
    /**
     * @todo Find objects in vendor dir
     */
    protected function getGeneratorYml($controller)
    {
        list($base, $bundle, $other) = explode('\\', $controller, 3);
                
        $finder = new Finder();
        $finder->files()
               ->name('generator.yml');
               
        $namespace_directory = realpath($this->container->getParameter('kernel.root_dir').'/../src/'.$base);
        
        if (is_dir($namespace_directory)) {
            $finder->in($namespace_directory);
            $it = $finder->getIterator();
            $it->rewind();

            return $it->current()->getRealpath();
        }

        throw new NotAdminGeneratedException;
    }

}
