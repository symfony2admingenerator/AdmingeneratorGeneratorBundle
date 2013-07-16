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
            'form_types' => array(
                'doctrine_orm' => array(
                    'datetime'      => 'datetime',
                    'vardatetime'   => 'datetime',
                    'datetimetz'    => 'datetime',
                    'date'          => 'datetime',
                    'time'          => 'time',
                    'decimal'       => 'number',
                    'float'         => 'number',
                    'integer'       => 'integer',
                    'bigint'        => 'integer',
                    'smallint'      => 'integer',
                    'string'        => 'text',
                    'text'          => 'textarea',
                    'entity'        => 'entity',
                    'collection'    => 'collection',
                    'array'         => 'collection',
                    'boolean'       => 'checkbox',
                ),
                'doctrine_odm' => array(
                    'datetime'      => 'datetime',
                    'timestamp'     => 'datetime',
                    'vardatetime'   => 'datetime',
                    'datetimetz'    => 'datetime',
                    'date'          => 'datetime',
                    'time'          => 'time',
                    'decimal'       => 'number',
                    'float'         => 'number',
                    'int'           => 'integer',
                    'integer'       => 'integer',
                    'int_id'        => 'integer',
                    'bigint'        => 'integer',
                    'smallint'      => 'integer',
                    'id'            => 'text',
                    'custom_id'     => 'text',
                    'string'        => 'text',
                    'text'          => 'textarea',
                    'document'      => 'document',
                    'collection'    => 'collection',
                    'hash'          => 'collection',
                    'boolean'       => 'checkbox',
                ),
                'propel' => array(
                    'TIMESTAMP'     => 'datetime',
                    'BU_TIMESTAMP'  => 'datetime',
                    'DATE'          => 'date',
                    'BU_DATE'       => 'date',
                    'TIME'          => 'time',
                    'FLOAT'         => 'number',
                    'REAL'          => 'number',
                    'DOUBLE'        => 'number',
                    'DECIMAL'       => 'number',
                    'TINYINT'       => 'integer',
                    'SMALLINT'      => 'integer',
                    'INTEGER'       => 'integer',
                    'BIGINT'        => 'integer',
                    'NUMERIC'       => 'integer',
                    'CHAR'          => 'text',
                    'VARCHAR'       => 'text',
                    'LONGVARCHAR'   => 'textarea',
                    'BLOB'          => 'textarea',
                    'CLOB'          => 'textarea',
                    'CLOB_EMU'      => 'textarea',
                    'model'         => 'model',
                    'collection'    => 'collection',
                    'PHP_ARRAY'     => 'collection',
                    'ENUM'          => 'choice',
                    'BOOLEAN'       => 'checkbox',
                    'BOOLEAN_EMU'   => 'checkbox',
                ),
            ),
            'filter_types' => array(
                'doctrine_orm' => array(
                    'text'          => 'text',
                    'collection'    => 'entity',
                    'boolean'       => 'choice',
                ),
                'doctrine_odm' => array(
                    'text'          => 'text',
                    'collection'    => 'document',
                    'hash'          => 'text',
                    'boolean'       => 'choice',
                ),
                'propel' => array(
                    'LONGVARCHAR'   => 'text',
                    'BLOB'          => 'text',
                    'CLOB'          => 'text',
                    'CLOB_EMU'      => 'text',
                    'collection'    => 'model',
                    'PHP_ARRAY'     => 'choice',
                    'BOOLEAN'       => 'choice',
                    'BOOLEAN_EMU'   => 'choice',
                ),
            ),
            'stylesheets'   => array(),
            'javascripts'   => array(),
        );

        if (!is_null($key) && array_key_exists($key, $defaultConfiguration)) {
            return $defaultConfiguration[$key];
        }

        return $defaultConfiguration;
    }
}
