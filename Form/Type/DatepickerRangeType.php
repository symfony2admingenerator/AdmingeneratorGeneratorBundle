<?php

namespace Admingenerator\GeneratorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DatepickerRangeType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        unset($options['years']); // TODO: check if this line can be removed

        // prepare default options
        $default = clone $options;
        unset($default['from'], $default['to']);

        $builder
               ->add('from', new DatepickerType(), array_merge($defaults, $options['from']))
               ->add('to',   new DatepickerType(), array_merge($defaults, $options['to']));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'form';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $years = range(date('Y'), date('Y') - 120);

        $resolver->setDefaults(
            array(
            'format'  => null,
            'years'   => $years,
            'to'      => array(
                'years'         =>  $years,
                'widget'        =>  'datepicker',
                'prepend'       =>  'date_range.to.label',
                'attr'          =>  array('class' => 'input-small')),
            'from'    => array(
                'years'         =>  $years,
                'widget'        =>  'datepicker',
                'prepend'       =>  'date_range.from.label',
                'attr'          =>  array('class' => 'input-small')),
            'widget'  =>  'datepicker_range')
        );

        $resolver->setAllowedValues(array(
            'input'           =>  array('string'),
            'widget'          =>  array('datepicker_range'),
            'weekstart'       =>  range(0, 6),
        ));

        $resolver->setAllowedTypes(array(
            'format'    =>  array('null', 'int', 'string'),
            'prepend'   =>  array('bool', 'string'),
            'autoclose' =>  array('bool'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'datepicker_range';
    }
}
