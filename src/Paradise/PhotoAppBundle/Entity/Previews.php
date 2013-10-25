<?php

namespace Paradise\PhotoAppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Previews
 *
 * @ORM\Table(name="previews")
 * @ORM\Entity
 */
class Previews
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
     * @ORM\Column(name="preview_img", type="blob", nullable=false)
     */
    private $previewImg;



    /**
     * Set previewImg
     *
     * @param string $previewImg
     * @return Previews
     */
    public function setPreviewImg($previewImg)
    {
        $this->previewImg = $previewImg;
    
        return $this;
    }

    /**
     * Get previewImg
     *
     * @return string 
     */
    public function getPreviewImg()
    {
        return $this->previewImg;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Previews
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
     * @return Previews
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