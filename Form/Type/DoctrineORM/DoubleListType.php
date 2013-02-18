<?php

namespace Admingenerator\GeneratorBundle\Form\Type\DoctrineORM;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DoubleListType extends EntityType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->replaceDefaults(array('multiple'=>true));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'doctrine_orm_double_list';
    }
}
