#!/usr/bin/php
<?php

require_once __DIR__.'/../../../../../app/bootstrap.php.cache';
require_once __DIR__.'/../../../../../app/AppKernel.php';
require_once __DIR__ . '/../../../../../app/autoload.php';

use Admingenerator\GeneratorBundle\Builder\Generator;
use Admingenerator\GeneratorBundle\Builder\ListBuilder;

$generator = new Generator(realpath(__DIR__).'/generator.yml');
$generator->addBuilder(new ListBuilder());

foreach ($generator->getBuilders() as $builder) {
	echo $builder->getCode();
}