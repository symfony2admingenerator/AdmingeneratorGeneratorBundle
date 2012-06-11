<?php

namespace Admingenerator\GeneratorBundle\Form\Type;

use Symfony\Bridge\Propel1\Form\Type\ModelType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PropelDoubleListType extends ModelType
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
        return 'propel_double_list';
    }

}
