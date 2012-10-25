<?php

namespace Admingenerator\GeneratorBundle\Form\Type;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\Extension\Core\Type\FileType;

class UploadType extends FileType
{
    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['multipart']                =   true;
        $view->vars['maxNumberOfFiles']         =   $options['maxNumberOfFiles'];
        $view->vars['maxFileSize']              =   $options['maxFileSize'];
        $view->vars['minFileSize']              =   $options['minFileSize'];
        $view->vars['acceptFileTypes']          =   $options['acceptFileTypes'];
        $view->vars['previewSourceFileTypes']   =   $options['previewSourceFileTypes'];
        $view->vars['previewSourceMaxFileSize'] =   $options['previewSourceMaxFileSize'];
        $view->vars['previewMaxWidth']          =   $options['previewMaxWidth'];
        $view->vars['previewMaxHeight']         =   $options['previewMaxHeight'];
        $view->vars['previewAsCanvas']          =   $options['previewAsCanvas'];
        $view->vars['prependFiles']             =   $options['prependFiles'];
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        
        $resolver->setDefaults(array(
            'maxNumberOfFiles'          =>  null,
            'maxFileSize'               =>  null,
            'minFileSize'               =>  null,
            'acceptFileTypes'           =>  '/.*$/i',
            'previewSourceFileTypes'    =>  '/^image\/(gif|jpeg|png)$/',
            'previewSourceMaxFileSize'  =>  5000000,
            'previewMaxWidth'           =>  80,
            'previewMaxHeight'          =>  80,
            'previewAsCanvas'           =>  true,
            'prependFiles'              =>  false
        ));
        
        $resolver->setAllowedTypes(array(
            'maxNumberOfFiles'          =>  array('integer', 'null'),
            'maxFileSize'               =>  array('integer', 'null'),
            'minFileSize'               =>  array('integer', 'null'),
            'acceptFileTypes'           =>  array('string'),
            'previewSourceFileTypes'    =>  array('string'),
            'previewSourceMaxFileSize'  =>  array('integer'),
            'previewMaxWidth'           =>  array('integer'),
            'previewMaxHeight'          =>  array('integer'),
            'previewAsCanvas'           =>  array('bool'),
            'prependFiles'              =>  array('bool')
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'upload';
    }
}