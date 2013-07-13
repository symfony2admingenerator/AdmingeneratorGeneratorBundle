<?php

namespace Admingenerator\GeneratorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class MenuCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $template = $container->getParameter('knp_menu.twig.template');
        
        // Overwrite the knp_menu twig template only if it's set to default
        if ('knp_menu.html.twig' === $template) {
            $container->setParameter('knp_menu.twig.template', 'AdmingeneratorGeneratorBundle:KnpMenu:knp_menu_trans.html.twig');
        }
    }
}
