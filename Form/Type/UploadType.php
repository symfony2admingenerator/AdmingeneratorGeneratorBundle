<?php

namespace Admingenerator\GeneratorBundle\Form\Type;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Admingenerator\GeneratorBundle\Form\EventListener\CaptureUploadListener;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UploadType extends CollectionType
{
    protected $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;       
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $prototype = $builder->create(
                $options['prototype_name'], 
                new \Admingenerator\GeneratorBundle\Form\Type\UploadedFileType(), 
                $options['options']
        );
        $builder->setAttribute('prototype', $prototype->getForm());
        
        $captureListener = new CaptureUploadListener(
            $builder->getFormFactory(),
            $options['options']['data_class']
        );
        $builder->addEventSubscriber($captureListener);
        
        $resizeListener = new ResizeFormListener(
            $builder->getFormFactory(),
            new \Admingenerator\GeneratorBundle\Form\Type\UploadedFileType(),
            $options['options'],
            $options['allow_add'],
            $options['allow_delete']
        );

        $builder->addEventSubscriber($resizeListener);
        
        $builder->setAttribute('thumbnail_generator', $this->container->getParameter('admingenerator.thumbnail_generator'));
    }
    
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
        $view->vars['thumbnailFilter']          =   $options['thumbnailFilter'];
        $view->vars['thumbnailGenerator']       =   $form->getConfig()->getAttribute('thumbnail_generator');        
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
            'prependFiles'              =>  false,
            'thumbnailFilter'           =>  null
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
            'prependFiles'              =>  array('bool'),
            'thumbnailFilter'           =>  array('string', 'null')
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