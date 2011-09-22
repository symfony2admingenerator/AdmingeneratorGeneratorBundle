<?php

namespace Admingenerator\GeneratorBundle\Generator;

use Doctrine\Common\Util\Inflector;

class PropelColumn extends Column
{
    public function getSortOn()
    {
        return $this->sortOn != "" ? $this->sortOn : Inflector::classify($this->name);
    }
}
