<?php

namespace Admingenerator\GeneratorBundle\Routing;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

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
        'batch' => array(
                    'pattern'      => '/batch',
                    'defaults'     => array(),
                    'requirements' => array(
                        '_method' => 'POST'
                    ),
                    'controller'   => 'list',
                ),
        'delete' => array(
                    'pattern'      => '/{pk}/delete',
                    'defaults'     => array(),
                    'requirements' => array(),
                ),
        'edit' => array(
                    'pattern'      => '/{pk}/edit',
                    'defaults'     => array(),
                    'requirements' => array(),
                ),
        'update' => array(
                    'pattern'      => '/{pk}/update',
                    'defaults'     => array(),
                    'requirements' => array(),
                    'controller'   => 'edit',
                ),
        'show' => array(
                    'pattern'      => '/{pk}/show',
                    'defaults'     => array(),
                    'requirements' => array()
                ),
        'new' => array(
                    'pattern'      => '/new',
                    'defaults'     => array(),
                    'requirements' => array(),
                ),
        'create' => array(
                    'pattern'      => '/create',
                    'defaults'     => array(),
                    'requirements' => array(),
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

    public function load($resource, $type = null)
    {
        $collection = new RouteCollection();
        $resource = str_replace('\\', '/', $resource);
        $namespace = $this->getNamespaceFromResource($resource);
        $bundle_name = $this->getBundleNameFromResource($resource);

        foreach ($this->actions as $controller => $datas) {
            $action = 'index';

            if ($controller_folder = $this->getControllerFolder($resource)) {
                $route_name = str_replace(array('/', '\\'),'_',$namespace).'_'.$bundle_name.'_'.$controller_folder.'_'.$controller;
            } else {
                $route_name = str_replace(array('/', '\\'),'_',$namespace).'_'.$bundle_name.'_'.$controller;
            }

            if (isset($datas['controller'])) {
                $action     = $controller;
                $controller = $datas['controller'];
            }

            if ($controller_folder) {
                $datas['defaults']['_controller'] = $namespace.'\\'.$bundle_name.'\\Controller\\'.$controller_folder.'\\'.ucfirst($controller).'Controller::'.$action.'Action';
            } else {
                $datas['defaults']['_controller'] = str_replace(array('/', '\\'),'_',$namespace).$bundle_name.':'.ucfirst($controller).':'.$action;
            }

            $route = new Route($datas['pattern'], $datas['defaults'], $datas['requirements']);
            $collection->add($route_name, $route);
            $collection->addResource(new FileResource($resource.ucfirst($controller).'Controller.php'));
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

    protected function getBundleNameFromResource($resource)
    {
        preg_match('#.+/(.+Bundle)/Controller?/(.*?)/?$#', $resource, $matches);

        return $matches[1];
    }

    protected function getNamespaceFromResource($resource)
    {
        preg_match('#.+/(.+)/(.+Bundle)/Controller?/(.*?)/?$#', $resource, $matches);

        return str_replace('/', '\\', $matches[1]);
    }
}
