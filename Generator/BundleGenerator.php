<?php

namespace Admingenerator\GeneratorBundle\Generator;

use Sensio\Bundle\GeneratorBundle\Generator\Generator as BaseBundleGenerator;

use Symfony\Component\Filesystem\Filesystem;

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

    protected $actions = array('New', 'List', 'Edit', 'Delete', 'Show', 'Actions');

    protected $forms = array('New', 'Filters', 'Edit');

    public function __construct(Filesystem $filesystem, $skeletonDir)
    {
        $this->filesystem = $filesystem;
        $this->skeletonDir = $skeletonDir;
        if (method_exists($this, 'setSkeletonDirs')) {
            $this->setSkeletonDirs($this->skeletonDir);
        }
    }

    public function setGenerator($generator)
    {
        $this->generator = $generator;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    public function generate($namespace, $bundle, $dir, $format, $structure, $generator, $modelName)
    {
        $dir .= '/'.strtr($namespace, '\\', '/');

        // Retrieves model folder depending of chosen ORM
        $modelFolder = '';
        switch ($generator) {
            case 'propel':
                $modelFolder = 'Model';
                break;
            case 'doctrine':
                $modelFolder = 'Entity';
                break;
            case 'doctrine_orm':
                $modelFolder = 'Document';
                break;
        }

        list( $namespace_prefix, $bundle_name) = explode('\\', $namespace, 2);
        $parameters = array(
            'namespace'        => $namespace,
            'bundle'           => $bundle,
            'generator'        => 'admingenerator.generator.'.$this->generator,
            'namespace_prefix' => $namespace_prefix,
            'bundle_name'      => $bundle_name,
            'model_folder'     => $modelFolder,
            'model_name'       => $modelName,
            'prefix'           => ucfirst($this->prefix),
        );

        if (!file_exists($dir.'/'.$bundle.'.php')) {
            $this->renderGeneratedFile('Bundle.php', $dir.'/'.$bundle.'.php', $parameters);
        }

        foreach ($this->actions as $action) {
            $parameters['action'] = $action;
            $this->renderGeneratedFile(
                'DefaultController.php',
                $dir.'/Controller/'.($this->prefix ? ucfirst($this->prefix).'/' : '').$action.'Controller.php',
                $parameters
            );

            $this->renderGeneratedFile(
                'index.html.twig',
                $dir.'/Resources/views/'.ucfirst($this->prefix).$action.'/index.html.twig',
                $parameters
            );
        }

        foreach ($this->forms as $form) {
            $parameters['form'] = $form;
            $this->renderGeneratedFile(
                'DefaultType.php',
                $dir.'/Form/Type/'.($this->prefix ? ucfirst($this->prefix).'/' : '').$form.'Type.php',
                $parameters
            );
        }

        $this->renderGeneratedFile(
            'generator.yml',
            $dir.'/Resources/config/'.($this->prefix ? ucfirst($this->prefix).'-' : '').'generator.yml',
            $parameters
        );
    }

    protected function renderGeneratedFile($template, $target, array $parameters)
    {
        if (method_exists($this, 'setSkeletonDirs')) {
            $this->renderFile($template, $target, $parameters);
        } else {
            $this->renderFile($this->skeletonDir, $template, $target, $parameters);
        }
    }
}
