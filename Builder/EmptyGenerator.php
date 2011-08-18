<?php

namespace Admingenerator\GeneratorBundle\Builder;

/**
 * @author Cedric LOMBARDOT
 */

use Symfony\Component\Yaml\Yaml;

class EmptyGenerator extends Generator
{


    /**
     * Init a new generator and automatically define the base of tempDir
     *
     */
    public function __construct()
    {
        $this->tempDir = realpath(sys_get_temp_dir()).DIRECTORY_SEPARATOR.self::TEMP_DIR_PREFIX;
        if (!is_dir($this->tempDir)) {
            mkdir($this->tempDir, 0777, true);
        }
    }
}
