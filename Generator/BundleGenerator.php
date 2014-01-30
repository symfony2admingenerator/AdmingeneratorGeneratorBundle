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

    protected $actions = array(
        'New'  => array('views' => array(
            'index',
            'form',
        )),
        'List' => array('views' => array(
            'index',
            'results',
            'filters',
            'row'
        )),
        'Excel' => array('views' => array()),
        'Edit' => array('views' => array(
            'index',
            'form',
        )),
        'Show' => array('views' => array('index')),
        'Actions' => array('views' => array('index'))
    );

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
            $this->renderGeneratedFile('Bundle.php.twig', $dir.'/'.$bundle.'.php', $parameters);
        }

        foreach ($this->actions as $action => $actionProperties) {
            $parameters['action'] = $action;

            $controllerFile = $dir.'/Controller/'
                    .($this->prefix ? ucfirst($this->prefix).'/' : '').$action.'Controller.php';
            $this->copyPreviousFile($controllerFile);
            $this->renderGeneratedFile(
                'DefaultController.php.twig',
                $controllerFile,
                $parameters
            );

            foreach ($actionProperties['views'] as $templateName) {
                $templateFile = $dir.'/Resources/views/'.ucfirst($this->prefix).$action.'/'.$templateName.'.html.twig';
                $this->copyPreviousFile($templateFile);
                $this->renderGeneratedFile(
                    'default_view.html.twig',
                    $templateFile,
                    $parameters + array('view' => $templateName)
                );
            }
        }

        foreach ($this->forms as $form) {
            $parameters['form'] = $form;

            $formFile = $dir.'/Form/Type/'.($this->prefix ? ucfirst($this->prefix).'/' : '').$form.'Type.php';
            $this->copyPreviousFile($formFile);
            $this->renderGeneratedFile(
                'DefaultType.php.twig',
                $formFile,
                $parameters
            );
        }

        $generatorFile = $dir.'/Resources/config/'.($this->prefix ? ucfirst($this->prefix).'-' : '').'generator.yml';
        $this->copyPreviousFile($generatorFile);
        $this->renderGeneratedFile(
            'generator.yml.twig',
            $generatorFile,
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

    protected function copyPreviousFile($oldname)
    {
        if (file_exists($oldname)) {
            $newname = $oldname.'~';

            // Find unused copy name
            if (file_exists($newname)) {
                $key = 0;
                do {
                    $key++;
                } while (file_exists($oldname.'~'.$key));

                $newname = $oldname.'~'.$key;
            }

            // Create new copy
            rename($oldname, $newname);
        }
    }
}
