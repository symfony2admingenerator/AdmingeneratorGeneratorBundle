<?php

namespace Admingenerator\GeneratorBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerAware;
use Knp\Menu\ItemInterface;

class DefaultMenuBuilder extends ContainerAware
{
    protected $factory;

    /**
     * @param \Knp\Menu\FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Request $request
     * @param Router  $router
     */
    public function createAdminMenu(Request $request)
    {
        $menu = $this->createMainMenu();

        $help = $menu->addChild('Overwrite this menu', array('uri' => '#'));
        $this->addMenuWithSubItemsProperties($help);

        $help->addChild('Configure menu class', array('uri' => 'https://github.com/knplabs/KnpMenuBundle/blob/master/Resources/doc/index.md'));
        $help->addChild('Configure php class to use', array('uri' => 'https://github.com/cedriclombardot/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/change-the-menu-class.markdown'));

        return $menu;
    }

    protected function createMainMenu($menuId = 'main_navigation')
    {
        $menu = $this->factory->createItem('root');
        $menu->setchildrenAttributes(array('id' => $menuId, 'class'=>'nav'));

        return $menu;
    }


    protected function addMenuWithSubItemsProperties(ItemInterface $menu)
    {
        $menu->setLinkAttributes(array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'));
        $menu->setChildrenAttributes(array('class' => 'dropdown-menu'));
        $menu->setAttributes(array('class' => 'dropdown'));
    }
}
