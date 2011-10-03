<?php

namespace Admingenerator\GeneratorBundle\CacheWarmer;

use Symfony\Component\Yaml\Yaml;

use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Generate all admingenerated bundle on warmup
 *
 * @author Cedric LOMBARDOT
 */
class GeneratorCacheWarmer implements CacheWarmerInterface
{

    protected $container;

    protected $finder;

    protected $yaml_datas = array();

    /**
     * Constructor.
     *
     * @param ContainerInterface      $container The dependency injection container
     */
    public function __construct(ContainerInterface $container, GeneratorFinder $finder)
    {
        $this->container = $container;
        $this->finder = $finder;
    }

    /**
     * Warms up the cache.
     *
     * @param string $cacheDir The cache directory
     */
    public function warmUp($cacheDir)
    {
        foreach ($this->finder->findAllGeneratorYamls() as $yaml)
        {
            $this->buildFromYaml($yaml);
        }
    }

    /**
     * Checks whether this warmer is optional or not.
     *
     * @return Boolean always true
     */
    public function isOptional()
    {
        return true;
    }

    protected function buildFromYaml($file)
    {
        $this->parseYaml($file);
        $service = $this->yaml_datas['generator'];

        $generator = $this->container->get($service);
        $generator->setGeneratorYml($file);

        if (preg_match('/\/([^\/]+?)-generator.yml$/', $file, $matches)) {
            $generator->setBaseGeneratorName(ucfirst($matches[1]));
        }

        $generator->build();
    }

    protected function parseYaml($file)
    {
       $this->yaml_datas = Yaml::parse($file);
    }

}
