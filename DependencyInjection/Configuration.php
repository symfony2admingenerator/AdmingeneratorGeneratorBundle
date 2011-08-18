<?php

namespace Admingenerator\GeneratorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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
        $rootNode = $treeBuilder->root('admingenerator_generator');

        $rootNode->children()
                    ->booleanNode('overwrite_if_exists')->defaultFalse()->end()
                ->end();

        return $treeBuilder;
    }

}