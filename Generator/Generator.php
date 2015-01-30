<?php

namespace Admingenerator\GeneratorBundle\Generator;

use Symfony\Component\Finder\Finder;

use Symfony\Component\DependencyInjection\ContainerAware;
use Admingenerator\GeneratorBundle\Validator\ValidatorInterface;
use Admingenerator\GeneratorBundle\Builder\Generator as AdminGenerator;
use Doctrine\Common\Cache as DoctrineCache;

abstract class Generator extends ContainerAware implements GeneratorInterface
{
    /**
     * @var string
     */
    protected $root_dir;

    /**
     * @var string
     */
    protected $cache_dir;

    /**
     * @var string
     */
    protected $generator_yaml;

    protected $fieldGuesser;

    /**
     * @var string
     */
    protected $base_generator_name;

    /**
     * @var array
     */
    protected $validators = array();

    /**
     * @var DoctrineCache\CacheProvider
     */
    protected $cacheProvider;

    /**
     * @var string
     */
    protected $cacheSuffix = 'default';

    public function __construct($root_dir, $cache_dir)
    {
        $this->root_dir = $root_dir;
        $this->cache_dir = $cache_dir;
        $this->cacheProvider = new DoctrineCache\ArrayCache();
    }

    /**
     * @param Doctrine\Common\Cache\CacheProvider $cacheProvider
     * @param string $cacheSuffix
     */
    public function setCacheProvider(DoctrineCache\CacheProvider $cacheProvider, $cacheSuffix = 'default')
    {
        $this->cacheProvider = $cacheProvider;
        $this->cacheSuffix = $cacheSuffix;
    }

    public function setGeneratorYml($yaml_file)
    {
        $this->generator_yaml = $yaml_file;
    }

    public function getGeneratorYml()
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
       return $this->cache_dir.'/Admingenerated/'.str_replace('\\', DIRECTORY_SEPARATOR, $namespace).$bundle_name;
    }

    /**
     * (non-PHPdoc)
     * @see Generator/Admingenerator\GeneratorBundle\Generator.GeneratorInterface::build()
     */
    public function build()
    {
        if ($this->cacheProvider->fetch($this->getCacheKey())) {
            return;
        }

        $this->doBuild();
        $this->cacheProvider->save($this->getCacheKey(), true);
    }

    /**
     * Process build
     */
    abstract protected function doBuild();

    /**
     * @return string
     */
    protected function getCacheKey()
    {
        return sprintf('admingen_isbuilt_%s_%s', $this->getBaseGeneratorName(), $this->cacheSuffix);
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
    public function needToOverwrite(AdminGenerator $generator)
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

        if ($files > 0) {
            return true;
        }

        $finder = new Finder();
        foreach ($finder->files()->in($cacheDir) as $file) {
            if (false !== strpos(file_get_contents($file), 'AdmingeneratorEmptyBuilderClass')) {
                return true;
            }
        }

        return false;
    }

    public function addValidator(ValidatorInterface $validator)
    {
        $this->validators[] = $validator;
    }

    public function validateYaml()
    {
        foreach ($this->validators as $validator) {
            $validator->validate($this);
        }
    }
}
