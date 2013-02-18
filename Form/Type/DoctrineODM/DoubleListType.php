<?php

namespace Admingenerator\GeneratorBundle\Form\Type\DoctrineODM;

use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DoubleListType extends DocumentType
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
        return 'double_list';
    }
}
