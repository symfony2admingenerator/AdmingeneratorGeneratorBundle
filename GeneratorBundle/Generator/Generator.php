<?php

namespace Admingenerator\GeneratorBundle\Generator;

use Admingenerator\GeneratorBundle\Exception\NotAdminGeneratedException;
use Symfony\Component\Finder\Finder;

use Admingenerator\GeneratorBundle\Builder\Generator as AdminGenerator;
use Admingenerator\GeneratorBundle\Builder\ListBuilderAction;
use Admingenerator\GeneratorBundle\Builder\ListBuilderTemplate;



abstract class Generator implements GeneratorInterface
{
    private $controller;

    private $action;
    
    protected $root_dir;
    
    protected $cache_dir;

    const SFY_BASE_DIR = '/../../../../'; //Go to /
    
    
    public function __construct($root_dir, $cache_dir)
    {
        $this->root_dir = $root_dir;
        $this->cache_dir = $cache_dir;
    }
    
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
               
        if(is_dir(realpath($this->root_dir.'/../src/'.$base)))
        {
            $finder->in(realpath($this->root_dir.'/../src/'.$base));
            foreach ($finder as $file) {
                return $file->getRealpath();
            }
        }
        
        throw new NotAdminGeneratedException;
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
    
}