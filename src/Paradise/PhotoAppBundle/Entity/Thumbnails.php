<?php

namespace Paradise\PhotoAppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Thumbnails
 *
 * @ORM\Table(name="thumbnails")
 * @ORM\Entity
 */
class Thumbnails
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="unique_id", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $uniqueId;

    /**
     * @var string
     *
     * @ORM\Column(name="thumbnail_img", type="blob", nullable=false)
     */
    private $thumbnailImg;



    /**
     * Set thumbnailImg
     *
     * @param string $thumbnailImg
     * @return Thumbnails
     */
    public function setThumbnailImg($thumbnailImg)
    {
        $this->thumbnailImg = $thumbnailImg;
    
        return $this;
    }

    /**
     * Get thumbnailImg
     *
     * @return string 
     */
    public function getThumbnailImg()
    {
        return $this->thumbnailImg;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Thumbnails
     */
    public function setId($id)
    {
        $this->id = $id;
    
        return $this;
    }

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
     * Set uniqueId
     *
     * @param string $uniqueId
     * @return Thumbnails
     */
    public function setUniqueId($uniqueId)
    {
        $this->uniqueId = $uniqueId;
    
        return $this;
    }

    /**
     * Get uniqueId
     *
     * @return string 
     */
    public function getUniqueId()
    {
        return $this->uniqueId;
    }
}