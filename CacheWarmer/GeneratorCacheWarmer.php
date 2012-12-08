<?php

namespace Admingenerator\GeneratorBundle\CacheWarmer;

use Symfony\Component\Yaml\Yaml;

use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Admingenerator\GeneratorBundle\Exception\GeneratedModelClassNotFoundException;
use Admingenerator\GeneratorBundle\ClassLoader\AdmingeneratedClassLoader;

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
     * @param ContainerInterface $container The dependency injection container
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
        foreach ($this->finder->findAllGeneratorYamls() as $yaml) {
            try {
                $this->buildFromYaml($yaml);
            } catch (GeneratedModelClassNotFoundException $e) {
                echo ">> Skip warmup ".$e->getMessage()."\n";
            }
        }

        /** 
         * Load class to avoid problem with other cache warmers
         * like JmsDiExtraBundle
         *
         * See issue #190
         */
        $AdmingeneratedClassLoader = new AdmingeneratedClassLoader;
        $AdmingeneratedClassLoader->setBasePath($cacheDir);
        $AdmingeneratedClassLoader->register();
    }

    /**
     * Checks whether this warmer is optional or not.
     *
     * @return Boolean always false
     */
    public function isOptional()
    {
        return false;
    }

    protected function buildFromYaml($file)
    {
        $this->parseYaml($file);
        $service = $this->yaml_datas['generator'];

        $generator = $this->container->get($service);
        $generator->setGeneratorYml($file);

        // windows support too
        if (preg_match('/(?:\/|\\\\)([^\/\\\\]+?)-generator.yml$/', $file, $matches)) {
            $generator->setBaseGeneratorName(ucfirst($matches[1]));
        } else {
            $generator->setBaseGeneratorName('');
        }

        $generator->build();
    }

    protected function parseYaml($file)
    {
        Yaml::enablePhpParsing(true);
        $this->yaml_datas = Yaml::parse($file);
    }

}
