<?php

namespace Admingenerator\GeneratorBundle\Builder;

/**
 * @author Cedric LOMBARDOT
 */

class EmptyGenerator extends Generator
{

    /**
     * Init a new generator and automatically define the base of temp directory.
     *
     * @param string $baseTempDir Optional base for temporary template files
     */
    public function __construct($baseTempDir = null)
    {
        if (null === $baseTempDir) {
            $baseTempDir = realpath(sys_get_temp_dir());
        }

        $this->tempDir = $baseTempDir.DIRECTORY_SEPARATOR.self::TEMP_DIR_PREFIX;

        if (!is_dir($this->tempDir)) {
            mkdir($this->tempDir, 0777, true);
        }
    }
}
