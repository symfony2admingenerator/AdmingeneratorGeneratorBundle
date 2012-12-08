<?php

namespace Admingenerator\GeneratorBundle\Validator;

use Admingenerator\GeneratorBundle\Generator\Generator;
use Admingenerator\GeneratorBundle\Exception\GeneratedModelClassNotFoundException;

class PropelModelClassValidator extends BaseValidator implements ValidatorInterface
{
    public function validate(Generator $generator)
    {
        $model = $this->getFromYaml($generator, 'params.model');
        $parts = explode('\\', $model);
        $modelName = $parts[sizeof($parts) -1];
        unset($parts[sizeof($parts) -1]);

        $model = implode('\\', $parts).'\\om\\Base'.$modelName;

        if (!class_exists($model)) {
            throw new GeneratedModelClassNotFoundException(sprintf('Unable to find class %s for %s', $model, $generator->getGeneratorYml()));
        }
    }
}