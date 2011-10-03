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
            if ($yamlIterator = $this->findGeneratorYamlInBundle($bundle)) {
                do {
                    $yamls[] = $yamlIterator->current()->getRealPath();
                } while($yamlIterator->next());
            }
        }


        return $this->yamls = $yamls;
    }

    /**
     * Find templates in the given bundle.
     *
     * @param BundleInterface $bundle The bundle where to look for templates
     *
     * @return Iterator|false The generator.yml if exists
     */
    private function findGeneratorYamlInBundle(BundleInterface $bundle)
    {
        if (!file_exists($bundle->getPath().'/Resources/config')) {
            return false;
        }

        $finder = new Finder();
        $finder->files()
               ->name('generator.yml')
               ->name('*-generator.yml')
               ->in($bundle->getPath().'/Resources/config');

        $it = $finder->getIterator();
        $it->rewind();

        if ($it->valid()) {

            return $it;
        }

        return false;
    }
}
