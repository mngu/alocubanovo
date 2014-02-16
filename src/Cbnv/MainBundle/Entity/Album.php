<?php

namespace Cbnv\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cbnv\MainBundle\Form\AlbumType;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Album
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Cbnv\MainBundle\Entity\AlbumRepository")
 */
class Album {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Photo", mappedBy="album", cascade={"remove", "persist"})
     */
    protected $photos;

    public function __construct() {
        $this->photos = new ArrayCollection();
    }

    public function __toString() {
        return $this->title;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Album
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Album
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Get form
     *
     * @return \AlbumType
     */
    public function getForm() {
        return new AlbumType();
    }


    /**
     * Add photos
     *
     * @param \Cbnv\MainBundle\Entity\Photo $photos
     *
     * @return Album
     */
    public function addPhoto(\Cbnv\MainBundle\Entity\Photo $photos) {
        $this->photos[] = $photos;

        return $this;
    }

    /**
     * Remove photos
     *
     * @param \Cbnv\MainBundle\Entity\Photo $photos
     */
    public function removePhoto(\Cbnv\MainBundle\Entity\Photo $photos) {
        $this->photos->removeElement($photos);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhotos() {
        return $this->photos;
    }

    /**
     * Set photos
     *
     * @param \Doctrine\Common\Collections\Collection $photos
     */
    public function setPhoto(\Doctrine\Common\Collections\Collection $photos) {
        $this->photos = $photos;

        return $this;
    }
}
