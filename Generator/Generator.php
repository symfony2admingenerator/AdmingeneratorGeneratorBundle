<?php

namespace Admingenerator\GeneratorBundle\Generator;

use Symfony\Component\Finder\Finder;

use Symfony\Component\DependencyInjection\ContainerAware;

use Admingenerator\GeneratorBundle\Builder\Generator as AdminGenerator;
use Admingenerator\GeneratorBundle\Builder\ListBuilderAction;
use Admingenerator\GeneratorBundle\Builder\ListBuilderTemplate;

abstract class Generator extends ContainerAware implements GeneratorInterface
{
    private $controller;

    private $action;

    protected $root_dir;

    protected $cache_dir;

    protected $generator_yaml;

    protected $fieldGuesser;

    protected $base_generator_name;

    public function __construct($root_dir, $cache_dir)
    {
        $this->root_dir = $root_dir;
        $this->cache_dir = $cache_dir;
    }

    public function setGeneratorYml($yaml_file)
    {
        $this->generator_yaml = $yaml_file;
    }

    protected function getGeneratorYml()
    {
        return $this->generator_yaml;
    }

    public function setBaseGeneratorName($base_generator_name)
    {
        $this->base_generator_name = $base_generator_name;
    }

    protected function getBaseGeneratorName()
    {
        return $this->base_generator_name;
    }

   /**
    * (non-PHPdoc)
    * @see Generator/Admingenerator\GeneratorBundle\Generator.GeneratorInterface::getCachePath()
    */
    public function getCachePath($namespace, $bundle_name)
    {
       return $this->cache_dir.'/Admingenerated/'.$namespace.$bundle_name;
    }

    /**
     * (non-PHPdoc)
     * @see Generator/Admingenerator\GeneratorBundle\Generator.GeneratorInterface::build()
     */
    public function build()
    {
        throw new \LogicException('Not implemented');
    }


    public function setFieldGuesser($fieldGuesser)
    {
        return $this->fieldGuesser = $fieldGuesser;
    }

    public function getFieldGuesser()
    {
        return $this->fieldGuesser;
    }

    /**
     * Check if we have to build file
     */
    public function needToOverwrite(\Admingenerator\GeneratorBundle\Builder\Generator $generator)
    {
        if ($this->container->getParameter('admingenerator.overwrite_if_exists')) {
            return true;
        }

        $cacheDir = $this->getCachePath($generator->getFromYaml('params.namespace_prefix'), $generator->getFromYaml('params.bundle_name'));

        if (!is_dir($cacheDir)) {
            return true;
        }

        $fileInfo = new \SplFileInfo($this->getGeneratorYml());

        $finder = new Finder();
        $files = $finder->files()
                        ->date('< '.date('Y-m-d H:i:s',$fileInfo->getMTime()))
                        ->in($cacheDir)
                        ->count();

        return $files > 0;
    }
}
