<?php

namespace Admingenerator\GeneratorBundle\Form\EventListener;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CaptureUploadListener implements EventSubscriberInterface
{
    /**
     * @var string Property edited with UploadType
     */
    protected $propertyName;

    /**
     * @var string UploadType data_class
     */
    protected $dataClass;

    /**
     * Used to revert changes if form is not valid.
     * @var Doctrine\Common\Collections\Collection Original collection
     */
    protected $originalFiles;
    
    /**
     * @var array Captured upload
     */
    protected $uploads;
    
    public function __construct($propertyName, $dataClass)
    {
        $this->propertyName = $propertyName;
        $this->dataClass = $dataClass;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_BIND => array('preBind', 0),
            FormEvents::BIND => array('onBind', 0),
            FormEvents::POST_BIND => array('postBind', 0),
        );
    }

    public function preBind(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
        
        // capture uploads and store them for onBind event
        $this->uploads = $data[$this->propertyName]['uploads'];
        // unset additional form data to prevent errors
        unset($data[$this->propertyName]['uploads']);
        
        $event->setData($data);
    }
    
    public function onBind(FormEvent $event) {
        $form = $event->getForm();
        $data = $event->getData();
        
        // save original files collection for postBind event
        $getter = 'get'.ucfirst($this->propertyName);
        $this->originalFiles = $data->$getter();
        
        // create file entites for each file
        foreach($this->uploads as $upload) {
            if($upload === null) return;
            $file = new $this->dataClass($upload, $data);

            if (!($file instanceof \Admingenerator\GeneratorBundle\Model\FileInterface)) {
                throw new UnexpectedTypeException($file, '\Admingenerator\GeneratorBundle\Model\FileInterface');
            }

            $data->$getter()->add($file);
        }

        $event->setData($data);
    }

    public function postBind(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();  
        
        if(!$form->isValid()) {        
            // remove files absent in the original collection
            $getter = 'get'.ucfirst($this->propertyName);    
            $data->$getter()->clear();
            
            foreach($this->originalFiles as $file) {
                $data->$getter()->add($file);
            }
            // TODO: find a way to restore $this->uploads to the form
            
            $event->setData($data);
        }
    }
}