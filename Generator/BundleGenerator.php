<?php


namespace Admingenerator\GeneratorBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator as BaseBundleGenerator;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\Container;

/**
 * Generates an admin bundle.
 *
 * @author Cedric LOMBARDOT
 */
class BundleGenerator extends BaseBundleGenerator
{
    private $filesystem;
    private $skeletonDir;

    protected $generator;

    protected $prefix;

    protected $actions = array('New', 'List', 'Edit', 'Delete');

    protected $forms = array('New', 'Filters', 'Edit');

    public function __construct(Filesystem $filesystem, $skeletonDir)
    {
        $this->filesystem = $filesystem;
        $this->skeletonDir = $skeletonDir;
    }

    public function setGenerator($generator)
    {
        $this->generator = $generator;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    public function generate($namespace, $bundle, $dir, $format, $structure)
    {
        $dir .= '/'.strtr($namespace, '\\', '/');

        list( $namespace_prefix, $bundle_name) = explode('\\', $namespace, 2);
        $parameters = array(
            'namespace'        => $namespace,
            'bundle'           => $bundle,
            'generator'        => 'admingenerator.generator.'.$this->generator,
            'namespace_prefix' => $namespace_prefix,
            'bundle_name'      => $bundle_name,
            'prefix'           => ucfirst($this->prefix),
        );

        if (!file_exists($dir.'/'.$bundle.'.php')) {
            $this->renderFile($this->skeletonDir, 'Bundle.php', $dir.'/'.$bundle.'.php', $parameters);
        }

        foreach ($this->actions as $action) {
            $parameters['action'] = $action;
            $this->renderFile($this->skeletonDir, 'DefaultController.php', $dir.'/Controller/'.($this->prefix ? ucfirst($this->prefix).'/' : '').$action.'Controller.php', $parameters);

            if ('Delete' !== $action) {
                $this->renderFile($this->skeletonDir, 'index.html.twig', $dir.'/Resources/views/'.ucfirst($this->prefix).$action.'/index.html.twig', $parameters);
            }
        }

        foreach ($this->forms as $form) {
            $parameters['form'] = $form;
            $this->renderFile($this->skeletonDir, 'DefaultType.php', $dir.'/Form/Type/'.($this->prefix ? ucfirst($this->prefix).'/' : '').$form.'Type.php', $parameters);
        }

        $this->renderFile($this->skeletonDir, 'generator.yml', $dir.'/Resources/config/'.($this->prefix ? ucfirst($this->prefix).'-' : '').'generator.yml', $parameters);
    }
}
