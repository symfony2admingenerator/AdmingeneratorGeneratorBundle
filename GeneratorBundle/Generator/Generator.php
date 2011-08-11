<?php

namespace Admingenerator\GeneratorBundle\Generator;

use Admingenerator\GeneratorBundle\Exception\NotAdminGeneratedException;
use Symfony\Component\Finder\Finder;

use Admingenerator\GeneratorBundle\Builder\Generator as AdminGenerator;
use Admingenerator\GeneratorBundle\Builder\ListBuilderAction;
use Admingenerator\GeneratorBundle\Builder\ListBuilderTemplate;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\Container;


class Generator extends ContainerAware implements GeneratorInterface
{
    private $controller;

    private $action;

    const SFY_BASE_DIR = '/../../../../'; //Go to /

    /**
     * (non-PHPdoc)
     * @see Generator/Admingenerator\GeneratorBundle\Generator.GeneratorInterface::setController()
     */
    public function setController($controller)
    {
        list($this->controller, $this->action) = explode('::', $controller, 2);
    }

    /**
     * @todo Find objects in vendor dir
     */
    protected function getGeneratorYml()
    {
        list($base, $bundle, $other ) = explode('\\',$this->controller, 3);

        $finder = new Finder;
        $finder->files()
               ->name('generator.yml');
        if (is_dir(realpath($this->container->getParameter('kernel.root_dir').'/../src/'.$base))) {
            $finder->in(realpath($this->container->getParameter('kernel.root_dir').'/../src/'.$base));
            foreach ($finder as $file) {
                return $file->getRealpath();
            }
        }

        throw new NotAdminGeneratedException;
    }

    public function build()
    {
        if (!file_exists($this->getGeneratorYml())) {
            return; //Stop execution this is not an admingenerated module
        }

        $generator = new AdminGenerator($this->getGeneratorYml());
        $generator->addBuilder(new ListBuilderAction());
        $generator->addBuilder(new ListBuilderTemplate());
        $generator->writeOnDisk(realpath(__DIR__.self::SFY_BASE_DIR).DIRECTORY_SEPARATOR.$generator->getFromYaml('params.base_dir'));

    }
}
