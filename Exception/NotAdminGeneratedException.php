<?php

namespace Admingenerator\GeneratorBundle\Exception;

/**
 * Throw when the bundle not contains a generator.yml file
 *
 * @author Cedric LOMBARDOT
 */
class NotAdminGeneratedException extends \LogicException
{
    public function __construct()
    {
        parent::__construct("This module is not admingenerated");
    }
}
