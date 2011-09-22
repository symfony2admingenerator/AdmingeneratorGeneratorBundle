<?php

namespace Admingenerator\GeneratorBundle\Form;

use Symfony\Component\Form\AbstractExtension;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DoctrineOrmExtension extends AbstractExtension
{
    protected $registry;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    protected function loadTypes()
    {
        return array(
            new Type\DoctrineDoubleListType($this->registry),
        );
    }
}
