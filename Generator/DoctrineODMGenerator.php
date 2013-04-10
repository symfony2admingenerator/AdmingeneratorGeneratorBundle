<?php

namespace Admingenerator\GeneratorBundle\Generator;

use Admingenerator\GeneratorBundle\Builder\Generator as AdminGenerator;
use Admingenerator\GeneratorBundle\Exception\CantGenerateException;

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

use Admingenerator\GeneratorBundle\Builder\DoctrineODM\ShowBuilderAction;
use Admingenerator\GeneratorBundle\Builder\DoctrineODM\ShowBuilderTemplate;

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
        $this->validateYaml();

        $generator = new AdminGenerator($this->cache_dir, $this->getGeneratorYml());
        $generator->setContainer($this->container);
        $generator->setBaseAdminTemplate($generator->getFromYaml('base_admin_template', $this->container->getParameter('admingenerator.base_admin_template')));
        $generator->setFieldGuesser($this->getFieldGuesser());
        $generator->setMustOverwriteIfExists($this->needToOverwrite($generator));
        $generator->setTemplateDirs(array_merge(
            $this->container->getParameter('admingenerator.doctrineodm_templates_dirs'),
            array(__DIR__.'/../Resources/templates/DoctrineODM')
        ));
        $generator->setBaseController('Admingenerator\GeneratorBundle\Controller\DoctrineODM\BaseController');
        $generator->setBaseGeneratorName($this->getBaseGeneratorName());

        $embed_types = $generator->getFromYaml('params.embed_types', array());

        foreach ($embed_types as $yaml_path) {
            $this->prebuildEmbedType($yaml_path, $generator);
        }

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

        if (array_key_exists('show', $builders)) {
            $generator->addBuilder(new ShowBuilderAction());
            $generator->addBuilder(new ShowBuilderTemplate());
        }

        $generator->writeOnDisk($this->getCachePath($generator->getFromYaml('params.namespace_prefix'), $generator->getFromYaml('params.bundle_name')));
    }

    public function prebuildEmbedType($yaml_path, $generator)
    {
        $pattern_string = '/(?<namespace_prefix>(?>.+\:)?.+)\:(?<bundle_name>.+Bundle)\:(?<generator_path>.*?)$/';

        if (preg_match($pattern_string, $yaml_path, $match_string)) {
            $namespace_prefix = $match_string['namespace_prefix'];
            $bundle_name      = $match_string['bundle_name'];
            $generator_path   = $match_string['generator_path'];
        } else {
            $namespace_prefix = $generator->getFromYaml('params.namespace_prefix');
            $bundle_name      = $generator->getFromYaml('params.bundle_name');
            $generator_path   = $yaml_path;
        }

        $dir = $namespace_prefix.'/'.$bundle_name;
        if (is_dir($src = realpath($this->container->getParameter('kernel.root_dir').'/../src/'.$dir.'/Resources/config'))) {
            $namespace_directory = $src;
        } else {
            $namespace_directory = realpath($this->container->getParameter('kernel.root_dir').'/../vendor/bundles/'.$dir.'/Resources/config');
        }

        $yaml_file = $namespace_directory.'/'.$generator_path;

        if (!file_exists($yaml_file)) {
            throw new CantGenerateException("Can't generate embed type for $yaml_file, file not found.");
        }

        $embedGenerator = new AdminGenerator($this->cache_dir, $yaml_file);
        $embedGenerator->setContainer($this->container);
        $embedGenerator->setBaseAdminTemplate($embedGenerator->getFromYaml('base_admin_template', $this->container->getParameter('admingenerator.base_admin_template')));
        $embedGenerator->setFieldGuesser($this->getFieldGuesser());
        $embedGenerator->setMustOverwriteIfExists($this->needToOverwrite($embedGenerator));
        $embedGenerator->setTemplateDirs(array_merge(
            $this->container->getParameter('admingenerator.doctrineodm_templates_dirs'),
            array(__DIR__.'/../Resources/templates/DoctrineODM')
        ));

        $embedGenerator->addBuilder(new EditBuilderType());

        $embedGenerator->writeOnDisk($this->getCachePath($embedGenerator->getFromYaml('params.namespace_prefix'), $generator->getFromYaml('params.bundle_name')));
    }
}
