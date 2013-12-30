<?php

namespace Admingenerator\GeneratorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;

use Admingenerator\GeneratorBundle\Exception\ModelManagerNotSelectedException;

class AdmingeneratorGeneratorExtension extends Extension implements PrependExtensionInterface
{
    /**
     * Prepend KnpMenuBundle config
     */
    public function prepend(ContainerBuilder $container)
    {
        $config = array('twig' => array(
            'template' => 'AdmingeneratorGeneratorBundle:KnpMenu:knp_menu_trans.html.twig'
        ));

        foreach ($container->getExtensions() as $name => $extension) {
            switch ($name) {
                case 'knp_menu':
                    $container->prependExtensionConfig($name, $config);
                    break;
            }
        }
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $container->setParameter('admingenerator.overwrite_if_exists', $config['overwrite_if_exists']);
        $container->setParameter('admingenerator.base_admin_template', $config['base_admin_template']);
        $container->setParameter('admingenerator.dashboard_welcome_path', $config['dashboard_welcome_path']);
        $container->setParameter('admingenerator.login_path', $config['login_path']);
        $container->setParameter('admingenerator.logout_path', $config['logout_path']);
        $container->setParameter('admingenerator.exit_path', $config['exit_path']);
        $container->setParameter('admingenerator.stylesheets', $config['stylesheets']);
        $container->setParameter('admingenerator.javascripts', $config['javascripts']);

        $this->processModelManagerConfiguration($config, $container);
        $this->processTwigConfiguration($config['twig'], $container);
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     * @throws ModelManagerNotSelectedException
     */
    private function processModelManagerConfiguration(array $config, ContainerBuilder $container)
    {
        if (!($config['use_doctrine_orm'] || $config['use_doctrine_odm'] || $config['use_propel'])) {
            throw new ModelManagerNotSelectedException();
        }

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        // Fix template_dirs
        $doctrine_template_dirs = $doctrineodm_template_dirs = $propel_template_dirs = array();
        foreach ($config['templates_dirs'] as $dir) {
            $doctrine_template_dirs[]    = $dir.'/Doctrine';
            $doctrineodm_template_dirs[] = $dir.'/DoctrineODM';
            $propel_template_dirs[]      = $dir.'/Propel';
        }

        if ($config['use_doctrine_orm']) {
            $loader->load('doctrine_orm.xml');
            $container->setParameter('admingenerator.doctrine_templates_dirs', $doctrine_template_dirs);

            $formTypes = $config['form_types']['doctrine_orm'];
            $filterTypes = $config['filter_types']['doctrine_orm'];
            $container->setParameter('admingenerator.doctrine_form_types', $formTypes);
            $container->setParameter('admingenerator.doctrine_filter_types', $filterTypes);
        }

        if ($config['use_doctrine_odm']) {
            $loader->load('doctrine_odm.xml');
            $container->setParameter('admingenerator.doctrineodm_templates_dirs', $doctrineodm_template_dirs);

            $formTypes = $config['form_types']['doctrine_odm'];
            $filterTypes = $config['filter_types']['doctrine_odm'];
            $container->setParameter('admingenerator.doctrineodm_form_types', $formTypes);
            $container->setParameter('admingenerator.doctrineodm_filter_types', $filterTypes);
        }

        if ($config['use_propel']) {
            $loader->load('propel.xml');
            $container->setParameter('admingenerator.propel_templates_dirs', $propel_template_dirs);

            $formTypes = $config['form_types']['propel'];
            $filterTypes = $config['filter_types']['propel'];
            $container->setParameter('admingenerator.propel_form_types', $formTypes);
            $container->setParameter('admingenerator.propel_filter_types', $filterTypes);
        }
    }

    /**
     * @param array $twigConfiguration
     * @param ContainerBuilder $container
     */
    private function processTwigConfiguration(array $twigConfiguration, ContainerBuilder $container)
    {
        $container->setParameter('admingenerator.twig', $twigConfiguration);

        if ($twigConfiguration['use_localized_date']) {
            // Register Intl extension for localized date
            $container->register('twig.extension.intl', 'Twig_Extensions_Extension_Intl')->addTag('twig.extension');
        }
    }

    public function getAlias()
    {
        return 'admingenerator_generator';
    }
}
