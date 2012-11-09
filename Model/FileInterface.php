<?php

namespace Admingenerator\GeneratorBundle\Model;

/**
 * Interface for Upload widget files.
 * 
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
interface FileInterface
{
    /**
     * @param Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param $entity Associated entity
     */
    public function __construct($file, $entity);
    
    /**
     * @return integer
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getSize();

    /**
     * @return string
     */
    public function getPreview();

    /**
     * @return string
     */
    public function getWebPath();
}
