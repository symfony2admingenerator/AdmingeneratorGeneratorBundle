<?php

namespace Admingenerator\GeneratorBundle\Routing\Manipulator;

/**
 * Changes the PHP code of a YAML routing file.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
use Sensio\Bundle\GeneratorBundle\Manipulator\Manipulator;

use Symfony\Component\DependencyInjection\Container;

class RoutingManipulator extends Manipulator
{
    private $file;

    /**
     * Constructor.
     *
     * @param string $file The YAML routing file path
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Adds a routing resource at the top of the existing ones.
     *
     * @param string $bundle
     * @param string $format
     * @param string $prefix
     * @param string $path
     *
     * @return Boolean true if it worked, false otherwise
     *
     * @throws \RuntimeException If bundle is already imported
     */
    public function addResource($bundle, $format, $prefix = null, $path = 'routing')
    {
        $current = '';
        if (file_exists($this->file)) {
            $current = file_get_contents($this->file);

            // Don't add same bundle twice
            if (false !== strpos($current, $bundle)) {
                throw new \RuntimeException(sprintf('Bundle "%s" is already imported.', $bundle));
            }
        } elseif (!is_dir($dir = dirname($this->file))) {
            mkdir($dir, 0777, true);
        }

        if (null === $prefix) {
            $prefix = '/admin/'.Container::underscore($bundle);
        }
        
        $code = sprintf("%s:\n", $bundle.('/' !== $prefix ? '_'.str_replace('/', '_', substr($prefix, 1)) : ''));
        if ('admingenerator' == $format) {
            $code .= sprintf("    resource: \"@%s/Controller/\"\n    type:     admingenerator\n", $bundle);
        } else {
            $code .= sprintf("    resource: \"@%s/Resources/config/%s.%s\"\n", $bundle, $path, $format);
        }
        $code .= sprintf("    prefix:   %s\n", $prefix);
        $code .= "\n";
        $code .= $current;

        if (false === file_put_contents($this->file, $code)) {
            return false;
        }

        return true;
    }
}
