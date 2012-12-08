<?php

namespace Admingenerator\GeneratorBundle\Generator;

use Admingenerator\GeneratorBundle\Builder\Generator as AdminGenerator;

use Admingenerator\GeneratorBundle\Builder\Propel\ListBuilderAction;
use Admingenerator\GeneratorBundle\Builder\Propel\ListBuilderTemplate;
use Admingenerator\GeneratorBundle\Builder\Propel\NestedListBuilderAction;
use Admingenerator\GeneratorBundle\Builder\Propel\NestedListBuilderTemplate;
use Admingenerator\GeneratorBundle\Builder\Propel\FiltersBuilderType;

use Admingenerator\GeneratorBundle\Builder\Propel\DeleteBuilderAction;

use Admingenerator\GeneratorBundle\Builder\Propel\EditBuilderAction;
use Admingenerator\GeneratorBundle\Builder\Propel\EditBuilderTemplate;
use Admingenerator\GeneratorBundle\Builder\Propel\EditBuilderType;

use Admingenerator\GeneratorBundle\Builder\Propel\NewBuilderAction;
use Admingenerator\GeneratorBundle\Builder\Propel\NewBuilderTemplate;
use Admingenerator\GeneratorBundle\Builder\Propel\NewBuilderType;

class PropelGenerator extends Generator
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
        $this->validateYaml();
        
        $generator = new AdminGenerator($this->cache_dir, $this->getGeneratorYml());

        $generator->setContainer($this->container);
        $generator->setBaseAdminTemplate($this->container->getParameter('admingenerator.base_admin_template'));
        $generator->setFieldGuesser($this->getFieldGuesser());
        $generator->setMustOverwriteIfExists($this->needToOverwrite($generator));
        $generator->setTemplateDirs(array_merge(
            $this->container->getParameter('admingenerator.propel_templates_dirs'),
            array(__DIR__.'/../Resources/templates/Propel')
        ));
        $generator->setBaseController('Admingenerator\GeneratorBundle\Controller\Propel\BaseController');
        $generator->setColumnClass('Admingenerator\GeneratorBundle\Generator\PropelColumn');
        $generator->setBaseGeneratorName($this->getBaseGeneratorName());

        $builders = $generator->getFromYaml('builders',array());

        if (array_key_exists('list',$builders)) {
            $generator->addBuilder(new ListBuilderAction());
            $generator->addBuilder(new ListBuilderTemplate());
            $generator->addBuilder(new FiltersBuilderType());
        }

        if (array_key_exists('nested_list',$builders)) {
            $generator->addBuilder(new NestedListBuilderAction());
            $generator->addBuilder(new NestedListBuilderTemplate());
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
