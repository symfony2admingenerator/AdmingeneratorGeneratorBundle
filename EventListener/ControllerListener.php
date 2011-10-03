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
        if (HttpKernelInterface::MASTER_REQUEST === $event->getRequestType()) {
            try {
                $controller = $event->getRequest()->attributes->get('_controller');

                if (strstr($controller, '::')) { //Check if its a "real controller" not assetic for example
                    $generatorYaml = $this->getGeneratorYml($controller);

                    $generator = $this->getGenerator($generatorYaml);
                    $generator->setGeneratorYml($generatorYaml);
                    $generator->setGeneratedControllerFolder('Base'.$this->getBaseGeneratorName($controller).'Controller');
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

    protected function getBaseGeneratorName($controller)
    {
        list($base, $bundle, $controllerFolder, $other) = explode('\\', $controller, 4);

        //Find if its a name-generator or generator.yml
        if (strstr($other, '\\')) {
            list($generatorName, $controllerName) = explode('\\', $other, 2);

            return $generatorName;
        }

        return '';
    }

    /**
     * @todo Find objects in vendor dir
     */
    protected function getGeneratorYml($controller)
    {
        list($base, $bundle, $controllerFolder, $other) = explode('\\', $controller, 4);

        $generatorName  = $this->getBaseGeneratorName($controller) ? strtolower($this->getBaseGeneratorName($controller)).'-' : '';
        $generatorName .= 'generator.yml';

        $finder = new Finder();
        $finder->files()
               ->name($generatorName);

        $namespace_directory = realpath($this->container->getParameter('kernel.root_dir').'/../src/'.$base.'/'.$bundle);

        if (is_dir($namespace_directory)) {
            $finder->in($namespace_directory);
            $it = $finder->getIterator();
            $it->rewind();

            if ($it->valid()) {

                return $it->current()->getRealpath();
            }
        }

        throw new NotAdminGeneratedException;
    }

}
