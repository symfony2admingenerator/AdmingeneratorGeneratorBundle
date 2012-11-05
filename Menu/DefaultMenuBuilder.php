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

        $this->addNavLinkURI($help, 'Configure menu class', 'https://github.com/knplabs/KnpMenuBundle/blob/master/Resources/doc/index.md');
        $this->addNavLinkURI($help, 'Configure php class to use', 'https://github.com/cedriclombardot/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/change-the-menu-class.markdown');

        return $menu;
    }
}
