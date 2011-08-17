<?php

namespace Admingenerator\GeneratorBundle\Generator;

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
}