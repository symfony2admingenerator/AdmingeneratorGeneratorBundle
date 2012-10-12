<?php

namespace Admingenerator\GeneratorBundle\Validator;

use Admingenerator\GeneratorBundle\Generator\Generator;
use Admingenerator\GeneratorBundle\Exception\ModelClassNotFoundException;

class ModelClassValidator extends BaseValidator implements ValidatorInterface
{
	public function validate(Generator $generator)
	{
		if (!$model = $this->getFromYaml($generator, 'params.model')) {
			throw new ModelClassNotFoundException(sprintf('You should define params.model option in %s', $generator->getGeneratorYml()));
		}

		if (!class_exists($model)) {
			throw new ModelClassNotFoundException(sprintf('Unable to find class %s for %s', $model,  $generator->getGeneratorYml()));
		}
	}
}

