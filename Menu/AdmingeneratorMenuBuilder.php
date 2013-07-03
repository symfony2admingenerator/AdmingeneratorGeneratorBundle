<?php

namespace Admingenerator\GeneratorBundle\Menu;

use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class AdmingeneratorMenuBuilder extends ContainerAware
{
    protected $dividers = array();

    /**
     * Creates header element and adds it to menu
     * 
     * @param \Knp\Menu\ItemInterface $menu
     * @param string $label Header label
     * @return ItemInterface Header element
     */
    protected function addNavHeader(ItemInterface $menu, $label)
    {
        $item = $menu->addChild($label);
        $item->setAttribute('class', 'nav-header');
        $item->setExtra('translation_domain', $menu->getExtra('translation_domain'));
        $menu->setExtra('request_uri', $menu->getExtra('request_uri'));

        return $item;
    }

    /**
     * Creates link to uri element and adds it to menu
     * 
     * @param \Knp\Menu\ItemInterface $menu
     * @param string $label Link label
     * @param string $route Link uri
     * @return ItemInterface Link element
     */
    protected function addNavLinkURI(ItemInterface $menu, $label, $uri)
    {
        $item = $menu->addChild($label, array('uri' => $uri));
        $item->setExtra('translation_domain', $menu->getExtra('translation_domain'));
        $menu->setExtra('request_uri', $menu->getExtra('request_uri'));

        if ($item->getUri() == $menu->getExtra('request_uri')) {
          $item->setAttribute('class', 'active');
        }

        return $item;
    }

    /**
     * Creates link to route element and adds it to menu
     * 
     * @param \Knp\Menu\ItemInterface $menu
     * @param string $label Link label
     * @param string $route Link route
     * @param array $routeParameters Route parameters
     * @return ItemInterface Link element
     */
    protected function addNavLinkRoute(ItemInterface $menu, $label, $route, $routeParameters = array())
    {
        $item = $menu->addChild($label, array('route' => $route, 'routeParameters' => $routeParameters));
        $item->setExtra('translation_domain', $menu->getExtra('translation_domain'));
        $menu->setExtra('request_uri', $menu->getExtra('request_uri'));

        if ($item->getUri() == $menu->getExtra('request_uri')) {
          $item->setAttribute('class', 'active');
        }

        return $item;
    }

    /**
     * Creates dropdown menu element and adds it to menu
     * 
     * @param \Knp\Menu\ItemInterface $menu
     * @param string $label Dropdown label
     * @param bool $caret Wheather or not append caret
     * @return ItemInterface Dropdown element
     */
    protected function addDropdownMenu(ItemInterface $menu, $label, $caret = true)
    {
        $item = $this->addNavLinkURI($menu, $label, '#');
        $item->setLinkAttributes(array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'));
        $item->setChildrenAttributes(array('class' => 'dropdown-menu'));
        $item->setAttributes(array('class' => 'dropdown'));
        $item->setExtra('translation_domain', $menu->getExtra('translation_domain'));
        $item->setExtra('caret', $caret);
        $menu->setExtra('request_uri', $menu->getExtra('request_uri'));

        return $item;
    }

    /**
     * Creates divider element and adds it to menu
     * 
     * @param \Knp\Menu\ItemInterface $menu
     * @return ItemInterface Divider element
     */
    protected function addDivider(ItemInterface $menu)
    {
        do {
            $name = 'divider'.mt_rand();
        } while (in_array($name, $this->dividers));

        $this->dividers[] = $name;

        $item = $menu->addChild($name, array());
        $item->setLabel('');
        $item->setAttribute('class', 'divider');

        return $item;
    }
}
