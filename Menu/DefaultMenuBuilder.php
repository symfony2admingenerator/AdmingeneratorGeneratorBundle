<?php

namespace Admingenerator\GeneratorBundle\Menu;

use Symfony\Component\HttpFoundation\Request;

class DefaultMenuBuilder extends AdmingeneratorMenuBuilder
{
    /**
     * @param Request $request
     * @param Router  $router
     */
    public function createAdminMenu(Request $request)
    {
        $menu = parent::createAdminMenu($request);

        /**
         * Translation domain is passed down to child elements
         * in addNavLinkURI, addNavLinkRoute, addDropdownMenu methods.
         */
        $menu->setExtra('translation_domain', 'Admingenerator');

        $help = $this->addDropdownMenu($menu, 'Overwrite this menu');

        $this->addNavLinkURI($help, 'Render your menu', 'https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/menu/menu.md');
        $this->addNavLinkURI($help, 'Configure menu cookbook', 'https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/cookbook/menu.md');

        return $menu;
    }
}
