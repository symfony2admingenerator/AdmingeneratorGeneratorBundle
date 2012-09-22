<?php

namespace Admingenerator\GeneratorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DatepickerRangeType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         unset($options['years']);

         $options['from']['required'] = $options['required'];
         $options['to']['required'] = $options['required'];

         if ($options['format']) {
            $options['from']['format'] = $options['format'];
            $options['to']['format'] = $options['format'];
         }

         $builder
               ->add('from', new DatepickerType(), $options['from'])
               ->add('to', new DatepickerType(), $options['to']);
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
    public function getDefaultOptions(array $options)
    {
        $years = range(date('Y'), date('Y') - 120);

        return array(
            'format'  => null,
            'years'   => $years,
            'to'      => array(
                'label'   =>  'date_range.to.label',
                'years'   =>  $years, 
                'widget'  =>  'datepicker',
                'prepend' =>  true,
                'attr'    =>  array('class' => 'input-small')),
            'from'    => array(
                'label'   =>  'date_range.from.label',
                'years'   =>  $years, 
                'widget'  =>  'datepicker',
                'prepend' =>  true,
                'attr'    =>  array('class' => 'input-small')),
            'widget'  =>  'datepicker_range',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'datepicker_range';
    }
}
