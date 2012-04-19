<?php

namespace Admingenerator\GeneratorBundle\Generator;

use Admingenerator\GeneratorBundle\Builder\Generator as AdminGenerator;

use Admingenerator\GeneratorBundle\Builder\DoctrineODM\ListBuilderAction;
use Admingenerator\GeneratorBundle\Builder\DoctrineODM\ListBuilderTemplate;
use Admingenerator\GeneratorBundle\Builder\DoctrineODM\FiltersBuilderType;

use Admingenerator\GeneratorBundle\Builder\DoctrineODM\DeleteBuilderAction;

use Admingenerator\GeneratorBundle\Builder\DoctrineODM\EditBuilderAction;
use Admingenerator\GeneratorBundle\Builder\DoctrineODM\EditBuilderTemplate;
use Admingenerator\GeneratorBundle\Builder\DoctrineODM\EditBuilderType;

use Admingenerator\GeneratorBundle\Builder\DoctrineODM\NewBuilderAction;
use Admingenerator\GeneratorBundle\Builder\DoctrineODM\NewBuilderTemplate;
use Admingenerator\GeneratorBundle\Builder\DoctrineODM\NewBuilderType;

class DoctrineODMGenerator extends Generator
{
    /**
     * (non-PHPdoc)
     * @see Generator/Admingenerator\GeneratorBundle\Generator.Generator::build()
     * @see http://juliusbeckmann.de/blog/php-benchmark-isset-or-array_key_exists.html
     *   // Key is NULL
     *   $array['key'] = NULL;
     *   var_dump(isset($array['key'])); // false
     *   var_dump(array_key_exists('key', $array)); // true
     */
    public function build()
    {
        $generator = new AdminGenerator($this->cache_dir, $this->getGeneratorYml());
        $generator->setContainer($this->container);
        $generator->setBaseAdminTemplate($this->container->getParameter('admingenerator.base_admin_template'));
        $generator->setFieldGuesser($this->getFieldGuesser());
        $generator->setMustOverwriteIfExists($this->container->getParameter('admingenerator.overwrite_if_exists'));
        $generator->setTemplateDirs(array(__DIR__.'/../Resources/templates/DoctrineODM'));
        $generator->setBaseController('Admingenerator\GeneratorBundle\Controller\DoctrineODM\BaseController');
        $generator->setBaseGeneratorName($this->getBaseGeneratorName());

        $builders = $generator->getFromYaml('builders',array());

        if (array_key_exists('list',$builders)) {
            $generator->addBuilder(new ListBuilderAction());
            $generator->addBuilder(new ListBuilderTemplate());
            $generator->addBuilder(new FiltersBuilderType());
        }

        if (array_key_exists('delete', $builders)) {
            $generator->addBuilder(new DeleteBuilderAction());
        }

        if (array_key_exists('edit', $builders)) {
            $generator->addBuilder(new EditBuilderAction());
            $generator->addBuilder(new EditBuilderTemplate());
            $generator->addBuilder(new EditBuilderType());
        }

        if (array_key_exists('new', $builders)) {
            $generator->addBuilder(new NewBuilderAction());
            $generator->addBuilder(new NewBuilderTemplate());
            $generator->addBuilder(new NewBuilderType());
        }

        $generator->writeOnDisk($this->getCachePath($generator->getFromYaml('params.namespace_prefix'), $generator->getFromYaml('params.bundle_name')));
    }
}
