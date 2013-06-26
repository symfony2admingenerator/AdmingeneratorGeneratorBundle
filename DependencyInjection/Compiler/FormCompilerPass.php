<?php

namespace Admingenerator\GeneratorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class FormCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (($twigConfiguration = $container->getParameter('admingenerator.twig')) !== false) {
            $resources = $container->getParameter('twig.form.resources');
            if ($twigConfiguration['use_form_resources'] && !in_array('AdmingeneratorGeneratorBundle:Form:fields.html.twig', $resources)) {
                // Insert right after form_div_layout.html.twig if exists
                if (($key = array_search('form_div_layout.html.twig', $resources)) !== false) {
                    array_splice($resources, ++$key, 0, array(
                        'AdmingeneratorGeneratorBundle:Form:fields.html.twig', 
                        'AdmingeneratorGeneratorBundle:Form:widgets.html.twig', 
                        'AdmingeneratorGeneratorBundle:Form:javascripts.html.twig', 
                        'AdmingeneratorGeneratorBundle:Form:stylesheets.html.twig'
                        ));
                } else {
                    // Put it in first position
                    array_unshift($resources, array(
                        'AdmingeneratorGeneratorBundle:Form:fields.html.twig', 
                        'AdmingeneratorGeneratorBundle:Form:widgets.html.twig', 
                        'AdmingeneratorGeneratorBundle:Form:javascripts.html.twig', 
                        'AdmingeneratorGeneratorBundle:Form:stylesheets.html.twig'
                    ));
                }

                $container->setParameter('twig.form.resources', $resources);
            }
        }
    }
}
