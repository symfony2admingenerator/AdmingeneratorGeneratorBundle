<?php

namespace Admingenerator\GeneratorBundle\Menu;

use Admingenerator\GeneratorBundle\Menu\AdmingeneratorMenuBuilder;
use Knp\Menu\FactoryInterface;

class DefaultMenuBuilder extends AdmingeneratorMenuBuilder
{
    protected $translation_domain = 'Admin';
    
    public function navbarMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttributes(array('id' => 'main_navigation', 'class' => 'nav'));
        
        $overwrite = $this->addDropdown($menu, 'Replace this menu');
        
        $this->addLinkURI(
            $overwrite,
            'Create new menu builder',
            'https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle'
            .'/blob/master/Resources/doc/cookbook/menu.md'
        )->setExtra('icon', 'icon-wrench');
        
        $this->addLinkURI(
            $overwrite,
            'Customize menu block',
            'https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle'.
            '/blob/master/Resources/views/base_admin_navbar.html.twig'
        )->setExtra('icon', 'icon-fork');
        
        return $menu;
    }
}
