<?php

namespace Admingenerator\GeneratorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle
 *
 * @author clombardot
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('admingenerator_generator');

        $rootNode
            ->children()
                ->booleanNode('use_doctrine_orm')->defaultFalse()->end()
                ->booleanNode('use_doctrine_odm')->defaultFalse()->end()
                ->booleanNode('use_propel')->defaultFalse()->end()
                ->booleanNode('overwrite_if_exists')->defaultFalse()->end()
                ->scalarNode('base_admin_template')
                    ->defaultValue("AdmingeneratorGeneratorBundle::base_admin.html.twig")
                ->end()
                ->scalarNode('dashboard_welcome_path')->defaultNull()->end()
                ->scalarNode('login_path')->defaultNull()->end()
                ->scalarNode('logout_path')->defaultNull()->end()
                ->scalarNode('exit_path')->defaultNull()->end()
                ->arrayNode('twig')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('use_form_resources')->defaultTrue()->end()
                        ->booleanNode('use_localized_date')->defaultFalse()->end()
                        ->scalarNode('date_format')->defaultValue('Y-m-d')->end()
                        ->scalarNode('datetime_format')->defaultValue('Y-m-d H:i:s')->end()
                        ->scalarNode('localized_date_format')->defaultValue('medium')->end()
                        ->scalarNode('localized_datetime_format')->defaultValue('medium')->end()
                        ->arrayNode('number_format')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('decimal')->defaultValue(0)->end()
                                ->scalarNode('decimal_point')->defaultValue('.')->end()
                                ->scalarNode('thousand_separator')->defaultValue(',')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('templates_dirs')
                    ->useAttributeAsKey('key')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('form_types')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('doctrine_orm')
                            ->addDefaultsIfNotSet()
                            ->children()
                                // datetime types
                                ->scalarNode('datetime')->defaultValue('datetime')->end()
                                ->scalarNode('vardatetime')->defaultValue('datetime')->end()
                                ->scalarNode('datetimetz')->defaultValue('datetime')->end()
                                ->scalarNode('date')->defaultValue('datetime')->end()
                                // time types
                                ->scalarNode('time')->defaultValue('time')->end()
                                // number types
                                ->scalarNode('decimal')->defaultValue('number')->end()
                                ->scalarNode('float')->defaultValue('number')->end()
                                // integer types
                                ->scalarNode('integer')->defaultValue('integer')->end()
                                ->scalarNode('bigint')->defaultValue('integer')->end()
                                ->scalarNode('smallint')->defaultValue('integer')->end()
                                // text types
                                ->scalarNode('string')->defaultValue('text')->end()
                                // textarea types
                                ->scalarNode('text')->defaultValue('textarea')->end()
                                // association types
                                ->scalarNode('entity')->defaultValue('entity')->end()
                                ->scalarNode('collection')->defaultValue('collection')->end()
                                // array types
                                ->scalarNode('array')->defaultValue('collection')->end()
                                // boolean types
                                ->scalarNode('boolean')->defaultValue('checkbox')->end()
                            ->end()
                        ->end()
                        ->arrayNode('doctrine_odm')
                            ->addDefaultsIfNotSet()
                            ->children()
                                // datetime types
                                ->scalarNode('datetime')->defaultValue('datetime')->end()
                                ->scalarNode('timestamp')->defaultValue('datetime')->end()
                                ->scalarNode('vardatetime')->defaultValue('datetime')->end()
                                ->scalarNode('datetimetz')->defaultValue('datetime')->end()
                                ->scalarNode('date')->defaultValue('datetime')->end()
                                // time types
                                ->scalarNode('time')->defaultValue('time')->end()
                                // number types
                                ->scalarNode('decimal')->defaultValue('number')->end()
                                ->scalarNode('float')->defaultValue('number')->end()
                                // integer types
                                ->scalarNode('int')->defaultValue('integer')->end()
                                ->scalarNode('integer')->defaultValue('integer')->end()
                                ->scalarNode('int_id')->defaultValue('integer')->end()
                                ->scalarNode('bigint')->defaultValue('integer')->end()
                                ->scalarNode('smallint')->defaultValue('integer')->end()
                                // text types
                                ->scalarNode('id')->defaultValue('text')->end()
                                ->scalarNode('custom_id')->defaultValue('text')->end()
                                ->scalarNode('string')->defaultValue('text')->end()
                                // textarea types
                                ->scalarNode('text')->defaultValue('textarea')->end()
                                // association types
                                ->scalarNode('document')->defaultValue('document')->end()
                                ->scalarNode('collection')->defaultValue('collection')->end()
                                // hash types
                                ->scalarNode('hash')->defaultValue('collection')->end()
                                // boolean types
                                ->scalarNode('boolean')->defaultValue('checkbox')->end()
                            ->end()
                        ->end()
                        ->arrayNode('propel')
                            ->addDefaultsIfNotSet()
                            ->children()
                                // datetime types
                                ->scalarNode('TIMESTAMP')->defaultValue('datetime')->end()
                                ->scalarNode('BU_TIMESTAMP')->defaultValue('datetime')->end()
                                // date types
                                ->scalarNode('DATE')->defaultValue('date')->end()
                                ->scalarNode('BU_DATE')->defaultValue('date')->end()
                                // time types
                                ->scalarNode('TIME')->defaultValue('time')->end()
                                // number types
                                ->scalarNode('FLOAT')->defaultValue('number')->end()
                                ->scalarNode('REAL')->defaultValue('number')->end()
                                ->scalarNode('DOUBLE')->defaultValue('number')->end()
                                ->scalarNode('DECIMAL')->defaultValue('number')->end()
                                // integer types
                                ->scalarNode('TINYINT')->defaultValue('integer')->end()
                                ->scalarNode('SMALLINT')->defaultValue('integer')->end()
                                ->scalarNode('INTEGER')->defaultValue('integer')->end()
                                ->scalarNode('BIGINT')->defaultValue('integer')->end()
                                ->scalarNode('NUMERIC')->defaultValue('integer')->end()
                                // text types
                                ->scalarNode('CHAR')->defaultValue('text')->end()
                                ->scalarNode('VARCHAR')->defaultValue('text')->end()
                                // textarea types
                                ->scalarNode('LONGVARCHAR')->defaultValue('textarea')->end()
                                ->scalarNode('BLOB')->defaultValue('textarea')->end()
                                ->scalarNode('CLOB')->defaultValue('textarea')->end()
                                ->scalarNode('CLOB_EMU')->defaultValue('textarea')->end()
                                // association types
                                ->scalarNode('model')->defaultValue('model')->end()
                                ->scalarNode('collection')->defaultValue('collection')->end()
                                // array types
                                ->scalarNode('PHP_ARRAY')->defaultValue('collection')->end()
                                // choice types
                                ->scalarNode('ENUM')->defaultValue('choice')->end()
                                // boolean types
                                ->scalarNode('BOOLEAN')->defaultValue('checkbox')->end()
                                ->scalarNode('BOOLEAN_EMU')->defaultValue('checkbox')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('filter_types')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('doctrine_orm')
                            ->addDefaultsIfNotSet()
                            ->children()
                                // datetime types
                                ->scalarNode('datetime')->defaultValue('datetime')->end()
                                ->scalarNode('vardatetime')->defaultValue('datetime')->end()
                                ->scalarNode('datetimetz')->defaultValue('datetime')->end()
                                ->scalarNode('date')->defaultValue('date')->end()
                                // time types
                                ->scalarNode('time')->defaultValue('time')->end()
                                // number types
                                ->scalarNode('decimal')->defaultValue('number')->end()
                                ->scalarNode('float')->defaultValue('number')->end()
                                // integer types
                                ->scalarNode('integer')->defaultValue('number')->end()
                                ->scalarNode('bigint')->defaultValue('number')->end()
                                ->scalarNode('smallint')->defaultValue('number')->end()
                                // text types
                                ->scalarNode('string')->defaultValue('text')->end()
                                // textarea types
                                ->scalarNode('text')->defaultValue('text')->end()
                                // association types
                                ->scalarNode('entity')->defaultValue('model')->end()
                                ->scalarNode('collection')->defaultValue('collection')->end()
                                // array types
                                ->scalarNode('array')->defaultValue('array')->end()
                                // boolean types
                                ->scalarNode('boolean')->defaultValue('boolean')->end()
                            ->end()
                        ->end()
                        ->arrayNode('doctrine_odm')
                            ->addDefaultsIfNotSet()
                            ->children()
                                // datetime types
                                ->scalarNode('datetime')->defaultValue('datetime')->end()
                                ->scalarNode('timestamp')->defaultValue('datetime')->end()
                                ->scalarNode('vardatetime')->defaultValue('datetime')->end()
                                ->scalarNode('datetimetz')->defaultValue('datetime')->end()
                                ->scalarNode('date')->defaultValue('date')->end()
                                // time types
                                ->scalarNode('time')->defaultValue('time')->end()
                                // number types
                                ->scalarNode('decimal')->defaultValue('number')->end()
                                ->scalarNode('float')->defaultValue('number')->end()
                                // integer types
                                ->scalarNode('int')->defaultValue('number')->end()
                                ->scalarNode('integer')->defaultValue('number')->end()
                                ->scalarNode('int_id')->defaultValue('number')->end()
                                ->scalarNode('bigint')->defaultValue('number')->end()
                                ->scalarNode('smallint')->defaultValue('number')->end()
                                // text types
                                ->scalarNode('id')->defaultValue('text')->end()
                                ->scalarNode('custom_id')->defaultValue('text')->end()
                                ->scalarNode('string')->defaultValue('text')->end()
                                // textarea types
                                ->scalarNode('text')->defaultValue('text')->end()
                                // association types
                                ->scalarNode('document')->defaultValue('model')->end()
                                ->scalarNode('collection')->defaultValue('collection')->end()
                                // hash types
                                ->scalarNode('hash')->defaultValue('array')->end()
                                // boolean types
                                ->scalarNode('boolean')->defaultValue('boolean')->end()
                            ->end()
                        ->end()
                        ->arrayNode('propel')
                            ->addDefaultsIfNotSet()
                            ->children()
                                // datetime types
                                ->scalarNode('TIMESTAMP')->defaultValue('datetime')->end()
                                ->scalarNode('BU_TIMESTAMP')->defaultValue('datetime')->end()
                                // date types
                                ->scalarNode('DATE')->defaultValue('date')->end()
                                ->scalarNode('BU_DATE')->defaultValue('date')->end()
                                // time types
                                ->scalarNode('TIME')->defaultValue('time')->end()
                                // number types
                                ->scalarNode('FLOAT')->defaultValue('number')->end()
                                ->scalarNode('REAL')->defaultValue('number')->end()
                                ->scalarNode('DOUBLE')->defaultValue('number')->end()
                                ->scalarNode('DECIMAL')->defaultValue('number')->end()
                                // integer types
                                ->scalarNode('TINYINT')->defaultValue('number')->end()
                                ->scalarNode('SMALLINT')->defaultValue('number')->end()
                                ->scalarNode('INTEGER')->defaultValue('number')->end()
                                ->scalarNode('BIGINT')->defaultValue('number')->end()
                                ->scalarNode('NUMERIC')->defaultValue('number')->end()
                                // text types
                                ->scalarNode('CHAR')->defaultValue('text')->end()
                                ->scalarNode('VARCHAR')->defaultValue('text')->end()
                                // textarea types
                                ->scalarNode('LONGVARCHAR')->defaultValue('text')->end()
                                ->scalarNode('BLOB')->defaultValue('text')->end()
                                ->scalarNode('CLOB')->defaultValue('text')->end()
                                ->scalarNode('CLOB_EMU')->defaultValue('text')->end()
                                // association types
                                ->scalarNode('model')->defaultValue('model')->end()
                                ->scalarNode('collection')->defaultValue('collection')->end()
                                // array types
                                ->scalarNode('PHP_ARRAY')->defaultValue('array')->end()
                                // choice types
                                ->scalarNode('ENUM')->defaultValue('text')->end()
                                // boolean types
                                ->scalarNode('BOOLEAN')->defaultValue('boolean')->end()
                                ->scalarNode('BOOLEAN_EMU')->defaultValue('boolean')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->append($this->getStylesheetNode())
                ->append($this->getJavascriptsNode())
            ->end();

        return $treeBuilder;
    }

    private function getStylesheetNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('stylesheets');

        $node
            ->prototype('array')
            ->fixXmlConfig('stylesheets')
                ->children()
                    ->scalarNode('path')->end()
                    ->scalarNode('media')->defaultValue('all')->end()
                ->end()
            ->end();

        return $node;
    }

    private function getJavascriptsNode()
    {
        $treeBuilder = new TreeBuilder();
        $node = $treeBuilder->root('javascripts');

        $node
            ->prototype('array')
            ->fixXmlConfig('javascripts')
                ->children()
                    ->scalarNode('path')->end()
                    ->scalarNode('route')->end()
                    ->arrayNode('routeparams')
                        ->useAttributeAsKey('key')
                        ->prototype('scalar')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $node;
    }
}
