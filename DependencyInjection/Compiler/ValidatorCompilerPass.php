<?php

namespace Admingenerator\GeneratorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class ValidatorCompilerPass implements CompilerPassInterface
{
	public function process(ContainerBuilder $container)
    {
        $taggedServices = $container->findTaggedServiceIds(
            'admingenerator.validator'
        );

        $taggedServicesPropel = $container->findTaggedServiceIds(
            'admingenerator.validator.propel'
        );

        $taggedServicesDoctrine = $container->findTaggedServiceIds(
            'admingenerator.validator.doctrine'
        );

        $taggedServicesDoctrineOdm = $container->findTaggedServiceIds(
            'admingenerator.validator.doctrine_odm'
        );


        if ($container->hasDefinition('admingenerator.generator.propel')) {
            $this->addValidators($taggedServicesPropel, $container->getDefinition('admingenerator.generator.propel'));
            $this->addValidators($taggedServices, $container->getDefinition('admingenerator.generator.propel'));
        }

        if ($container->hasDefinition('admingenerator.generator.doctrine')) {
            $this->addValidators($taggedServicesDoctrine, $container->getDefinition('admingenerator.generator.doctrine'));
            $this->addValidators($taggedServices, $container->getDefinition('admingenerator.generator.doctrine'));
        }

        if ($container->hasDefinition('admingenerator.generator.doctrine_odm')) {
            $this->addValidators($taggedServicesDoctrineOdm, $container->getDefinition('admingenerator.generator.doctrine_odm'));
            $this->addValidators($taggedServices, $container->getDefinition('admingenerator.generator.doctrine_odm'));
        }
    }

    protected function addValidators(array $taggedServices, Definition $definition)
    {
    	foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall(
                'addValidator',
                array(new Reference($id))
            );
        }
    }
}