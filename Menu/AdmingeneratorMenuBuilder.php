<?php

namespace Admingenerator\GeneratorBundle\Menu;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class AdmingeneratorMenuBuilder extends ContainerAware
{
    protected $factory;

    /**
     * @param \Knp\Menu\FactoryInterface $factory
     * 
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Request $request
     * @param Router $router
     */
    public function createAdminMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttributes(array('id' => 'main_navigation', 'class' => 'nav'));

        return $menu;
    }

    /**
     * @param Request $request
     * @param Router $router
     */
    public function createDashboardMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');

        $menu->setChildrenAttributes(array('id' => 'dashboard_sidebar', 'class' => 'nav nav-list'));
        $menu->setExtra('request_uri', $this->container->get('request')->getRequestUri());
        $menu->setExtra('translation_domain', 'Admingenerator');
        
        $this->addNavHeader($menu, 'Overview');
        $this->addNavLinkRoute($menu, 'Dashboard', 'AdmingeneratorDashboard_welcome')->setExtra('icon', 'icon-home');
        $this->addNavHeader($menu, 'Features');
        $this->addNavLinkRoute($menu, 'Commands', 'AdmingeneratorDashboard_documentation', array('document' => 'commands'))->setExtra('icon', 'icon-bullhorn');
        $this->addNavLinkRoute($menu, 'Filters', 'AdmingeneratorDashboard_documentation', array('document' => 'filters'))->setExtra('icon', 'icon-filter');
        $this->addNavLinkRoute($menu, 'Routing', 'AdmingeneratorDashboard_documentation', array('document' => 'routing'))->setExtra('icon', 'icon-globe');
        $this->addNavLinkRoute($menu, 'Forms', 'AdmingeneratorDashboard_documentation', array('document' => 'forms'))->setExtra('icon', 'icon-list');
        $this->addNavLinkRoute($menu, 'Templates', 'AdmingeneratorDashboard_documentation', array('document' => 'templates'))->setExtra('icon', 'icon-th-large');
        $this->addNavLinkRoute($menu, 'Model manager', 'AdmingeneratorDashboard_documentation', array('document' => 'orm'))->setExtra('icon', 'icon-random');

        return $menu;
    }
    
    protected function addNavHeader(ItemInterface $menu, $label)
    {
        $item = $menu->addChild($label);
        $item->setAttribute('class', 'nav-header');
        $item->setExtra('translation_domain', $menu->getExtra('translation_domain'));
        $menu->setExtra('request_uri', $menu->getExtra('request_uri'));
        
        return $item;
    }
    
    protected function addNavLinkURI(ItemInterface $menu, $label, $uri)
    {
        $item = $menu->addChild($label, array('uri' => $uri));
        $item->setExtra('translation_domain', $menu->getExtra('translation_domain'));
        $menu->setExtra('request_uri', $menu->getExtra('request_uri'));
        
        if($item->getUri() == $menu->getExtra('request_uri')) {
          $item->setAttribute('class', 'active');
        }
        
        return $item;
    }
    
    protected function addNavLinkRoute(ItemInterface $menu, $label, $route, $routeParameters = array())
    {
        $item = $menu->addChild($label, array('route' => $route, 'routeParameters' => $routeParameters));
        $item->setExtra('translation_domain', $menu->getExtra('translation_domain'));
        $menu->setExtra('request_uri', $menu->getExtra('request_uri'));
        
        if($item->getUri() == $menu->getExtra('request_uri')) {
          $item->setAttribute('class', 'active');
        }
        
        return $item;
    }
    
    protected function addDropdownMenu(ItemInterface $menu, $label)
    {
        $item = $this->addNavLinkURI($menu, $label, '#');
        $item->setLinkAttributes(array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'));
        $item->setChildrenAttributes(array('class' => 'dropdown-menu'));
        $item->setAttributes(array('class' => 'dropdown'));
        $item->setExtra('translation_domain', $menu->getExtra('translation_domain'));
        $menu->setExtra('request_uri', $menu->getExtra('request_uri'));
        
        return $item;
    }
}
