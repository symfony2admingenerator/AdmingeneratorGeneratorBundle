<?php

namespace Admingenerator\GeneratorBundle\CacheWarmer;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\Finder\Finder;

/**
 * Finds all the generator.yml.
 *
 * @author Cedric LOMBARDOT
 */
class GeneratorFinder
{
    private $kernel;
    private $yamls;

    /**
     * Constructor.
     *
     * @param KernelInterface $kernel A KernelInterface instance
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
            foreach ($this->findGeneratorYamlInBundle($bundle) as $yaml) {
                $yamls[$yaml] = $yaml;
            }
        }

        return $this->yamls = $yamls;
    }

    /**
     * Find templates in the given bundle.
     *
     * @param BundleInterface $bundle The bundle where to look for templates
     *
     * @return array of yaml paths
     */
    private function findGeneratorYamlInBundle(BundleInterface $bundle)
    {
        $yamls =  array();

        if (!file_exists($bundle->getPath().'/Resources/config')) {
            return $yamls;
        }

        $finder = new Finder();
        $finder->files()
               ->name('generator.yml')
               ->name('*-generator.yml')
               ->in($bundle->getPath().'/Resources/config');

        foreach ($finder as $file) {
            $yamls[$file->getRealPath()] = $file->getRealPath();
        }

        return $yamls;
    }
}
