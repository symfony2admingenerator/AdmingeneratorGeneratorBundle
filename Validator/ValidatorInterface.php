<?php

namespace Admingenerator\GeneratorBundle\Validator;

use Admingenerator\GeneratorBundle\Generator\Generator;

interface ValidatorInterface
{
	public function validate(Generator $generator);
}