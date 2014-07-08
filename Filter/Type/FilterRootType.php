<?php

namespace Admingenerator\GeneratorBundle\Filter\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class FilterRootType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'type'              => 'admingenerator_filter_group',
            'allow_add'         => true,
            'allow_delete'      => true,
            'prototype'         => true,
            'prototype_name'    => '__filter_group_name__',
            'options'           => array(
                'filters' => array()
            )
        ));
        
        $resolver->setAllowedValues(array(
            'type'          => array('admingenerator_filter_group'),
            'allow_add'     => array(true),
            'allow_delete'  => array(true),
            'prototype'     => array(true)
        ));
    }
    
    public function getParent()
    {
        return 'collection';
    }

    public function getName()
    {
        return 'admingenerator_filter_root';
    }
}
