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
                    $generator->setBaseGeneratorName($this->getBaseGeneratorName($controller));
                    $generator->build();

                }
            } catch (NotAdminGeneratedException $e) {
                //Lets the word running this is not an admin generated module
            }
        }

        if ($this->container->hasParameter('admingenerator.twig')) {
            $twig_params = $this->container->getParameter('admingenerator.twig');

            if (isset($twig_params['date_format'])) {
                $this->container->get('twig')->getExtension('core')->setDateFormat($twig_params['date_format'], '%d days');
            }

            if (isset($twig_params['number_format'])) {
                $this->container->get('twig')->getExtension('core')->setNumberFormat($twig_params['number_format']['decimal'], $twig_params['number_format']['decimal_point'], $twig_params['number_format']['thousand_separator']);
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
        preg_match('/(.+)Controller(.+)::.+/', $controller, $matches);

        //Find if its a name-generator or generator.yml
        if (isset($matches[2]) && strstr($matches[2], '\\')) {
            if (3 != count(explode('\\', $matches[2]))) {
                return '';
            }

            list($firstSlash, $generatorName) = explode('\\', $matches[2], 3);

            return $generatorName;
        }

        return '';
    }

    /**
     * @todo Find objects in vendor dir
     */
    protected function getGeneratorYml($controller)
    {
        preg_match('/(.+)?Controller.+::.+/', $controller, $matches);
        $dir = str_replace('\\', DIRECTORY_SEPARATOR, $matches[1]);

        $generatorName  = $this->getBaseGeneratorName($controller) ? $this->getBaseGeneratorName($controller).'-' : '';
        $generatorName .= 'generator.yml';

        $finder = new Finder();
        $finder->files()
               ->name($generatorName);

        if (is_dir($src = realpath($this->container->getParameter('kernel.root_dir').'/../src/'.$dir.'/Resources/config'))) {
            $namespace_directory = $src;
        } else {
            $namespace_directory = realpath($this->container->getParameter('kernel.root_dir').'/../vendor/bundles/'.$dir.'/Resources/config');
        }

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
