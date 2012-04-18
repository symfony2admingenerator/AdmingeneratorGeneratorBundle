<?php

namespace Admingenerator\GeneratorBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;


class DateRangeType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilder $builder, array $options)
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
    public function getParent(array $options)
    {
        return 'form';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultOptions()
    {
        $years = range(date('Y'), date('Y') - 120);

        return array(
            'format'            => null,
            'years'             => $years,
            'to'                => array('years' => $years, 'widget' => 'choice'),
            'from'              => array('years' => $years, 'widget' => 'choice'),
            'widget'            => 'choice',
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
