<?php

namespace Admingenerator\GeneratorBundle\Filter\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class FilterGroupType extends AbstractType
{
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['fields'] = $options['fields'];
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'type'              => 'admingenerator_filter_item',
            'allow_add'         => true,
            'allow_delete'      => true,
            'prototype'         => false,
            'prototype_name'    => '__filter_form_name__',
            'fields'            => array(),
        ));

        $resolver->setAllowedTypes(array(
            'fields' => array('array'),
        ));
        
        $resolver->setAllowedValues(array(
            'type'          => array('admingenerator_filter_item'),
            'allow_add'     => array(true),
            'allow_delete'  => array(true),
            'prototype'     => array(false),
        ));
    }
    
    public function getParent()
    {
        return 'collection';
    }

    public function getName()
    {
        return 'admingenerator_filter_group';
    }
}
