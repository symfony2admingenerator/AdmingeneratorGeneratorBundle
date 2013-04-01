<?php

namespace Admingenerator\GeneratorBundle\Form\EventListener;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SingleUploadNamerListener implements EventSubscriberInterface
{
    /**
     * @var string Property edited with SingleUploadType
     */
    protected $propertyName;

    /**
     * @var string Nameable property
     */
    protected $nameable;

    /**
     * @var string Used to revert changes if form is not valid.
     */
    protected $originalName;

    /**
     * @var string Captured name
     */
    protected $capturedName;

    /**
     * @var bool True if deletable behavior is enabled
     */
    protected $deleteable;

    /**
     * @var bool True if delete was clicked
     */
    protected $deleteFlag;

    /**
     * @var bool Used to revert changes if form is not valid.
     */
    protected $originalFile;

    public function __construct($propertyName, $nameable, $deleteable)
    {
        $this->propertyName = $propertyName;
        $this->nameable     = $nameable;
        $this->deleteable   = $deleteable;
        $this->capturedName = null;
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
        $data = $event->getData();

        if ($this->nameable && array_key_exists($this->propertyName.'_nameable', $data)) {
            // capture name and store it for onBind event
            $this->capturedName = $data[$this->propertyName.'_nameable'];

            // unset additional form data to prevent errors
            unset($data[$this->propertyName.'_nameable']);
        }

        if ($this->deleteable && array_key_exists($this->propertyName.'_delete', $data)) {
            // capture delete flag and store it for onBind event
            $this->deleteFlag = $data[$this->propertyName.'_delete'];
            // unset additional form data to prevent errors
            unset($data[$this->propertyName.'_delete']);
        }

        $event->setData($data);
    }

    public function onBind(FormEvent $event)
    {
        $data = $event->getData();

        if ($this->nameable) {
            $getterName = 'get'.ucfirst($this->nameable);
            $setterName = 'set'.ucfirst($this->nameable);

            // save original name for postBind event
            $this->originalName = $data->$getterName();

            // set new name
            $data->$setterName($this->capturedName);
        }

        $event->setData($data);
    }

    public function postBind(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (!$form->isValid()) {
            if ($this->nameable) {
                // revert to original name
                $setter = 'set'.ucfirst($this->nameable);
                $data->$setter($this->originalName);
            }

            $event->setData($data);
        }
    }
}
