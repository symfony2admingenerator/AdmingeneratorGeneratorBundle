<?php


namespace Admingenerator\GeneratorBundle\CacheWarmer;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * Finds all the generator.yml.
 *
 * @author Cedric LOMBARDOT
 */
class GeneratorFinder
{
    private $kernel;
    private $rootDir;
    private $yamls;

    /**
     * Constructor.
     *
     * @param KernelInterface      $kernel  A KernelInterface instance
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Find all the generator.yml in the bundle and in the kernel Resources folder.
     *
     * @return array An array of yaml files
     */
    public function findAllGeneratorYamls()
    {
        if (null !== $this->yamls) {
            return $this->yamls;
        }

        $yamls = array();

        foreach ($this->kernel->getBundles() as $name => $bundle) {
            if($yaml = $this->findGeneratorYamlInBundle($bundle)) {
                $yamls[] = $yaml;
            }
        }


        return $this->yamls = $yamls;
    }

    /**
     * Find templates in the given bundle.
     *
     * @param BundleInterface $bundle The bundle where to look for templates
     *
     * @return string|false The generator.yml if exists
     */
    private function findGeneratorYamlInBundle(BundleInterface $bundle)
    {
        $file = $bundle->getPath().'/Resources/config/generator.yml';
        
        if(file_exists($file)) {
            return $file;
        }
        
        return false;
    }
}
