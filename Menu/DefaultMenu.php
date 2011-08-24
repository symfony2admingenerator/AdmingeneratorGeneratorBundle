<?php 

namespace Admingenerator\GeneratorBundle\Menu;

use Knp\Bundle\MenuBundle\Menu;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

class DefaultMenu extends Menu
{
    /**
     * @param Request $request
     * @param Router $router
     */
    public function __construct(Request $request, Router $router)
    {
        parent::__construct();

        $this->setCurrentUri($request->getRequestUri());
        
        $this->setAttributes(array('id' => 'main_navigation', 'class'=>'menu'));

        $help = $this->addChild('Overwrite this menu', '#');
        $help->setLinkAttributes(array('class'=>'sub main'));
        
        $help->addChild('Configure menu class', 'https://github.com/knplabs/KnpMenuBundle/blob/master/Resources/doc/03-Twig-Integration.markdown');
        $help->addChild('Configure php class to use', 'https://github.com/cedriclombardot/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/change-the-menu-class.markdown');
        
    }
}