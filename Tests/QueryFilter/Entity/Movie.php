<?php
namespace Admingenerator\GeneratorBundle\Tests\QueryFilter\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Admingenerator\GeneratorBundle\Tests\QueryFilter\Entity\MovieRepository")
 * @ORM\Table(name="movies")
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(length="255")
     * @ORM\Index
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable="true")
     * @ORM\Index
     */
    protected $desc;

    /**
     * @ORM\Column(type="boolean", nullable="true")
     */
    protected $is_published;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set desc
     *
     * @param string $desc
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
    }

    /**
     * Get desc
     *
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * Set is_published
     *
     * @param boolean $isPublished
     */
    public function setIsPublished($isPublished)
    {
        $this->is_published = $isPublished;
    }

    /**
     * Get is_published
     *
     * @return boolean
     */
    public function getIsPublished()
    {
        return $this->is_published;
    }
}
