<?php

namespace Admingenerator\GeneratorBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UploadedFileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * by default form has no fields, if you want to 
         * add sortable or nameable behavior you should
         * extend this form and use your custom form instead
         */
    }

    public function getName()
    {
        return 'uploaded_file';
    }
}