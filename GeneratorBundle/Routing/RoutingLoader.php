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
        'delete' => array(
                    'pattern'      => '/{id}/delete',
                    'defaults'     => array(),
                    'requirements' => array(),
                ),
        'edit' => array(
                    'pattern'      => '/{id}/edit',
                    'defaults'     => array(),
                    'requirements' => array(),
                ),        
    );
    
    public function load($resource, $type = null)
    {
        $collection = new RouteCollection();
        
        $namespace = $this->getNamespaceFromResource($resource);
        $bundle_name = $this->getBundleNameFromResource($resource);
        
        foreach ($this->actions as $controller => $datas) {
            $datas['defaults']['_controller'] = $namespace.$bundle_name.':'.ucfirst($controller).':index'; 
            
            $route = new Route($datas['pattern'],$datas['defaults'], $datas['requirements']);
            $collection->add($bundle_name.'_'.$controller, $route);
            $collection->addResource(new FileResource($resource.ucfirst($controller).'Controller.php'));
        }

        return $collection;
    }
    
    public function supports($resource, $type = null)
    {
        return 'admingenerator' == $type;
    }
    
    protected function getBundleNameFromResource($resource)
    {
        preg_match('#.+/(.+Bundle)/Controller?/$#', $resource, $matches);
        
        return $matches[1];
    }
    
    protected function getNamespaceFromResource($resource)
    {
        preg_match('#.+/(.+)/(.+Bundle)/Controller?/$#', $resource, $matches);

        return $matches[1];
    }
}