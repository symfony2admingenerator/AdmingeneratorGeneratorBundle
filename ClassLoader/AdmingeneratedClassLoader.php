<?php

namespace Admingenerator\GeneratorBundle\ClassLoader;

use Admingenerator\GeneratorBundle\Builder\Admin\EmptyBuilderAction;
use Admingenerator\GeneratorBundle\Builder\EmptyGenerator;

/**
 * This class autoload admingenarated & if they not exists try to generate
 */
class AdmingeneratedClassLoader
{
    protected $base_path;

    /**
     * Registers this instance as an autoloader.
     *
     * @param Boolean $prepend Whether to prepend the autoloader or not
     */
    public function register($prepend = false)
    {
        spl_autoload_register(array($this, 'loadClass'), true, $prepend);
    }

    public function setBasePath($base_path)
    {
        return $this->base_path = $base_path;
    }

    /**
     * Loads the given class or interface.
     *
     * @param string $class The name of the class
     */
    public function loadClass($class)
    {
        if (0 === strpos($class, 'Admingenerated')) {
            $file_path = $this->base_path.DIRECTORY_SEPARATOR.str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';

            if (!file_exists($file_path)) {
                $this->generateEmptyController($class);
            }

            if (file_exists($file_path)) {
                require $file_path;
            }
        }
    }

    protected function generateEmptyController($class)
    {
        $generator = new EmptyGenerator($this->base_path);

        $parts = explode('\\',$class);
        $controllerName = $parts[count($parts) - 1];
        unset($parts[count($parts) - 1]);

        $namespace = implode('\\', $parts);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $class);

        $builder = new EmptyBuilderAction();
        $generator->addBuilder($builder);
        $builder->setOutputName($fileName.'.php');

        $builder->setVariables(array(
            'controllerName' => $controllerName,
            'namespace'      => $namespace,
        ));

        $generator->writeOnDisk($this->base_path);
    }

}
