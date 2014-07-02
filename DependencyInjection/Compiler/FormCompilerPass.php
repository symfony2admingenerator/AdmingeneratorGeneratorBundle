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
            $alreadyIn = in_array('AdmingeneratorGeneratorBundle:Form:fields.html.twig', $resources);
            
            if ($twigConfiguration['use_form_resources'] && !$alreadyIn) {
                $formTemplates = array(
                    'AdmingeneratorGeneratorBundle:Form:fields.html.twig',
                    'AdmingeneratorGeneratorBundle:Form:filters.html.twig'
                );
                
                if (($key = array_search('form_div_layout.html.twig', $resources)) !== false) {
                    // Insert right after form_div_layout.html.twig if exists
                    array_splice($resources, ++$key, 0, $formTemplates);
                } else {
                    // Put it in first position
                    array_unshift($resources, $formTemplates);
                }

                $container->setParameter('twig.form.resources', $resources);
            }
        }
    }
}
