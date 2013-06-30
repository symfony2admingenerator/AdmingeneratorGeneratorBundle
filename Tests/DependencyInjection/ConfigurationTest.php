<?php

namespace Admingenerator\GeneratorBundle\Tests\DependencyInjection;

use Admingenerator\GeneratorBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that default configuration is correctly initialized
     */
    public function testDefaultConfig()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), array());

        $this->assertEquals($this->getBundleDefaultConfig(), $config);
    }

    /**
     * Get waiting default values from configuration. If $key is not null
     * and is in first level keys, returns value of this specific key only.
     *
     * @param  string $key
     * @return mixed
     */
    private function getBundleDefaultConfig($key = null)
    {
        static $defaultConfiguration = array(
            'use_doctrine_orm' => false,
            'use_doctrine_odm' => false,
            'use_propel'       => false,
            'overwrite_if_exists' => false,
            'base_admin_template' => 'AdmingeneratorGeneratorBundle::base_admin.html.twig',
            'dashboard_welcome_path' => null,
            'login_path' => null,
            'logout_path' => null,
            'exit_path' => null,
            'knp_menu_class'      => 'Admingenerator\GeneratorBundle\Menu\DefaultMenuBuilder',
            'thumbnail_generator' => null,
            'twig'         => array(
                'use_form_resources' => true,
                'use_localized_date' => false,
                'date_format'        => 'Y-m-d',
                'datetime_format'    => 'Y-m-d H:i:s',
                'localized_date_format'     => 'medium',
                'localized_datetime_format' => 'medium',
                'number_format' => array(
                        'decimal'            => 0,
                        'decimal_point'      => '.',
                        'thousand_separator' => ','
                        )
            ),
            'templates_dirs' => array(),
            'stylesheets'   => array(),
            'javascripts'   => array()
        );

        if (!is_null($key) && array_key_exists($key, $defaultConfiguration)) {
            return $defaultConfiguration[$key];
        }

        return $defaultConfiguration;
    }
}
