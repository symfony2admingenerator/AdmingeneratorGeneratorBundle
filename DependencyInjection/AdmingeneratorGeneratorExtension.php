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


        // Fix template_dirs
        $doctrine_template_dirs = $doctrineodm_template_dirs = $propel_template_dirs = array();
        $config['templates_dirs'] = isset($config['templates_dirs'])  ? $config['templates_dirs'] : array();  
        foreach ($config['templates_dirs'] as $dir) {
            $doctrine_template_dirs[]    = $dir.'/Doctrine';
            $doctrineodm_template_dirs[] = $dir.'/DoctrineODM';
            $propel_template_dirs[]      = $dir.'/Propel';
        }

        if ($config['use_doctrine_orm']) {
            $loader->load('doctrine.xml');
            $container->setParameter('admingenerator.doctrine_templates_dirs', $doctrine_template_dirs);
        }

        if ($config['use_doctrine_odm']) {
            $loader->load('doctrine_odm.xml');
            $container->setParameter('admingenerator.doctrineodm_templates_dirs', $doctrineodm_template_dirs);
        }

        if ($config['use_propel']) {
            $loader->load('propel.xml');
            $container->setParameter('admingenerator.propel_templates_dirs', $propel_template_dirs);
        }

        $container->setParameter('admingenerator.thumbnail_generator', $config['thumbnail_generator']);
        $container->setParameter('admingenerator.overwrite_if_exists', $config['overwrite_if_exists']);
        $container->setParameter('admingenerator.base_admin_template', $config['base_admin_template']);
        $container->setParameter('admingenerator.menu_builder.class', $config['knp_menu_class']);
        $container->setParameter('admingenerator.stylesheets', $config['stylesheets']);
        $container->setParameter('admingenerator.javascripts', $config['javascripts']);

        $container->setParameter('session.flashbag.class', 'Symfony\Component\HttpFoundation\Session\Flash\FlashBag');

        $resources = $container->getParameter('twig.form.resources');
        $resources[] = 'AdmingeneratorGeneratorBundle:Form:fields.html.twig';
        $container->setParameter('twig.form.resources', $resources);
        
        $date_type = array(
                'class' => 'Admingenerator\GeneratorBundle\Form\Type\DateType',
                'tags' => array('name' => 'form.type', 'alias' => 'date'),
            );
        $container->setParameter('services.form.type.date', $date_type);

        if (!isset($config['twig'])) {
            $config['twig'] = array(
                'use_localized_date' => false,
                'date_format'        => 'Y-m-d',
                'datetime_format'    => 'Y-m-d H:i:s',
                'number_format'      => array(
                    'decimal'            => 0,
                    'decimal_point'      => '.',
                    'thousand_separator' => ',',
                )
             );
        }

        $container->setParameter('admingenerator.twig', $config['twig']);

        if ($config['twig']['use_localized_date']) {
            // Register Intl extension for localized date
            $container->register('twig.extension.intl', 'Twig_Extensions_Extension_Intl')
                        ->addTag('twig.extension');
        }     

    }

    public function getAlias()
    {
        return 'admingenerator_generator';
    }
}
