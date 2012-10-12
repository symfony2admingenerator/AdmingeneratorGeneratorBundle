<?php

namespace Admingenerator\GeneratorBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DateRangeType extends AbstractType
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
               ->add('from', new DateType(), $options['from'])
               ->add('to', new DateType(), $options['to']);
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
                'widget'  =>  'single_text', 
                'attr'    =>  array('class' => 'input-small')),
            'from'    => array(
                'label'   =>  'date_range.from.label',
                'years'   =>  $years, 
                'widget'  =>  'single_text', 
                'attr'    =>  array('class' => 'input-small')),
            'widget'  => 'single_text',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'date_range';
    }
}
