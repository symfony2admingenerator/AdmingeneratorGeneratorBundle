<?php

namespace Admingenerator\GeneratorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;


class AdmingeneratorGeneratorExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        if ($config['use_doctrine_orm']) {
            $loader->load('doctrine.xml');
        }

        if ($config['use_doctrine_odm']) {
            $loader->load('doctrine_odm.xml');
        }

        if ($config['use_propel']) {
            $loader->load('propel.xml');
        }

        $container->setParameter('admingenerator.overwrite_if_exists', $config['overwrite_if_exists']);
        $container->setParameter('admingenerator.base_admin_template', $config['base_admin_template']);
        $container->setParameter('admingeneretor.menu_builder.class', $config['knp_menu_class']);

        $resources = $container->getParameter('twig.form.resources');
        $resources[] = 'AdmingeneratorGeneratorBundle:Form:fields.html.twig';
        $container->setParameter('twig.form.resources', $resources);
    }

    public function getAlias()
    {
        return 'admingenerator_generator';
    }
}
