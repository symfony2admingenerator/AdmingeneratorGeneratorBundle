<?php

namespace Admingenerator\GeneratorBundle\Routing;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Config\Exception\FileLoaderLoadException;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class RoutingLoader extends FileLoader
{
    // Assoc beetween a controller and is route path
    //@todo make an object for this
    protected $actions = array(
        'list' => array(
                    'pattern'      => '/',
                    'defaults'     => array(),
                    'requirements' => array(),
                ),
        'excel'=> array(
                    'pattern'      => '/excel',
                    'defaults'     => array(),
                    'requirements' => array(),
                    'controller'   => 'excel',
                ),
        'edit' => array(
                    'pattern'      => '/{pk}/edit',
                    'defaults'     => array(),
                    'requirements' => array(),
                ),
        'update' => array(
                    'pattern'      => '/{pk}/update',
                    'defaults'     => array(),
                    'requirements' => array(
                        '_method' => 'POST'
                    ),
                    'controller'   => 'edit',
                ),
        'show' => array(
                    'pattern'      => '/{pk}/show',
                    'defaults'     => array(),
                    'requirements' => array(),
                ),
        'object' => array(
                    'pattern'      => '/{pk}/{action}',
                    'defaults'     => array(),
                    'requirements' => array(),
                    'controller'   => 'actions',
                ),
        'batch' => array(
                    'pattern'      => '/batch',
                    'defaults'     => array(),
                    'requirements' => array(
                        '_method' => 'POST'
                    ),
                    'controller'   => 'actions',
                ),
        'new' => array(
                    'pattern'      => '/new',
                    'defaults'     => array(),
                    'requirements' => array(),
                    'methods'      => array(),
                ),
        'create' => array(
                    'pattern'      => '/create',
                    'defaults'     => array(),
                    'requirements' => array(
                        '_method' => 'POST'
                    ),
                    'controller'   => 'new',
                ),
        'filters' => array(
                    'pattern'      => '/filter',
                    'defaults'     => array(),
                    'requirements' => array(),
                    'controller'   => 'list',
                ),
        'scopes' => array(
                    'pattern'      => '/scope/{group}/{scope}',
                    'defaults'     => array(),
                    'requirements' => array(),
                    'controller'   => 'list',
                ),
    );

    /**
     * @var array
     */
    protected $yaml = array();

    public function load($resource, $type = null)
    {
        $collection = new RouteCollection();

        $resource = str_replace('\\', '/', $resource);
        $this->yaml = Yaml::parse($this->getGeneratorFilePath($resource));

        $namespace = $this->getNamespaceFromResource($resource);
        $fullBundleName = $this->getFullBundleNameFromResource($resource);
        $bundle_name = $this->getBundleNameFromResource($resource);

        foreach ($this->actions as $controller => $datas) {
            $action = 'index';

            $loweredNamespace = str_replace(array('/', '\\'), '_', $namespace);
            if ($controller_folder = $this->getControllerFolder($resource)) {
                $route_name = $loweredNamespace . '_' . $bundle_name . '_' . $controller_folder . '_' . $controller;
            } else {
                $route_name = $loweredNamespace . '_' . $bundle_name . '_' . $controller;
            }

            if (in_array($controller, array('edit', 'update', 'object', 'show')) &&
                null !== $pk_requirement = $this->getFromYaml('params.pk_requirement', null)) {
                $datas['requirements'] = array_merge(
                    $datas['requirements'],
                    array('pk' => $pk_requirement)
                );
            }

            if (isset($datas['controller'])) {
                $action     = $controller;
                $controller = $datas['controller'];
            }

            $controllerName = $resource.ucfirst($controller).'Controller.php';
            if (is_file($controllerName)) {
                if ($controller_folder) {
                    $datas['defaults']['_controller'] = $namespace . '\\'
                            . $bundle_name . '\\Controller\\'
                            . $controller_folder . '\\'
                            . ucfirst($controller) . 'Controller::'
                            . $action . 'Action';
                } else {
                    $datas['defaults']['_controller'] = $loweredNamespace
                            . $bundle_name . ':'
                            . ucfirst($controller) . ':' . $action;
                }

                $route = new Route($datas['pattern'], $datas['defaults'], $datas['requirements']);
                $collection->add($route_name, $route);
                $collection->addResource(new FileResource($controllerName));
            }
        }

        return $collection;
    }

    public function supports($resource, $type = null)
    {
        return 'admingenerator' == $type;
    }

    protected function getControllerFolder($resource)
    {
        preg_match('#.+/.+Bundle/Controller?/(.*?)/?$#', $resource, $matches);

        return $matches[1];
    }

    protected function getFullBundleNameFromResource($resource)
    {
        // Find the *Bundle.php
        $finder = Finder::create()
            ->name('*Bundle.php')
            ->depth(0)
            ->in(realpath($resource.'/../../')) // ressource is controller folder
            ->getIterator();
        $finder->rewind();
        $file = $finder->current();

        if ($file) {
            if (PHP_VERSION_ID >= 50306) {
                return $file->getBasename('.' . $file->getExtension());
            }

            return $file->getBasename('.' . pathinfo($file->getFilename(), PATHINFO_EXTENSION));
        }

        return null;
    }

    protected function getBundleNameFromResource($resource)
    {
        preg_match('#.+/(.+Bundle)/Controller?/(.*?)/?$#', $resource, $matches);

        return $matches[1];
    }

    protected function getNamespaceFromResource($resource)
    {
        $finder = Finder::create()
            ->name('*Bundle.php')
            ->depth(0)
            ->in(realpath($resource.'/../../')) // ressource is controller folder
            ->getIterator();

        foreach ($finder as $file) {
            preg_match('/namespace (.+);/', file_get_contents($file->getRealPath()), $matches);

            return implode('\\', explode('\\', $matches[1], -1)); // Remove the short bundle name
        }
    }

    protected function getGeneratorFilePath($resource)
    {
        // Find the *-generator.yml
        $finder = Finder::create()
            ->name($this->getControllerFolder($resource).'-generator.yml')
            ->depth(0)
            ->in(realpath($resource.'/../../Resources/config/'))
            ->getIterator();

        foreach ($finder as $file) {
            return $file->getRealPath();
        }
    }

    /**
     * @param $yaml_path string with point for levels
     */
    public function getFromYaml($yaml_path, $default = null)
    {
        $search_in = $this->yaml;
        $yaml_path = explode('.', $yaml_path);
        foreach ($yaml_path as $key) {
            if (!isset($search_in[$key])) {
                return $default;
            }
            $search_in = $search_in[$key];
        }

        return $search_in;
    }
}
