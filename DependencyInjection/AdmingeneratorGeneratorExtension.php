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
        $container->setParameter('admingenerator.stylesheets', $config['stylesheets']);

        $container->setParameter('session.flashbag.class', 'Symfony\Component\HttpFoundation\Session\Flash\FlashBag');

        $resources = $container->getParameter('twig.form.resources');
        $resources[] = 'AdmingeneratorGeneratorBundle:Form:fields.html.twig';
        $container->setParameter('twig.form.resources', $resources);

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
