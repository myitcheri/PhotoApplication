<?php

namespace Paradise\PhotoAppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Photos
 *
 * @ORM\Table(name="photos")
 * @ORM\Entity
 */
class Photos
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255, nullable=false)
     */
    private $filename;

    /**
     * @var string
     *
     * @ORM\Column(name="photo_desc", type="text", nullable=true)
     */
    private $photoDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="org_img", type="blob", nullable=false)
     */
    private $orgImg;

    /**
     * @var string
     *
     * @ORM\Column(name="filesize", type="string", length=45, nullable=false)
     */
    private $filesize;

    /**
     * @var string
     *
     * @ORM\Column(name="filetype", type="string", length=255, nullable=false)
     */
    private $filetype;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datepost", type="datetime", nullable=false)
     */
    private $datepost;

    /**
     * @var string
     *
     * @ORM\Column(name="unique_id", type="string", length=255, nullable=false)
     */
    private $uniqueId;



    /**
     * Set filename
     *
     * @param string $filename
     * @return Photos
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    
        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set photoDesc
     *
     * @param string $photoDesc
     * @return Photos
     */
    public function setPhotoDesc($photoDesc)
    {
        $this->photoDesc = $photoDesc;
    
        return $this;
    }

    /**
     * Get photoDesc
     *
     * @return string 
     */
    public function getPhotoDesc()
    {
        return $this->photoDesc;
    }

    /**
     * Set orgImg
     *
     * @param string $orgImg
     * @return Photos
     */
    public function setOrgImg($orgImg)
    {
        $this->orgImg = $orgImg;
    
        return $this;
    }

    /**
     * Get orgImg
     *
     * @return string 
     */
    public function getOrgImg()
    {
        return $this->orgImg;
    }

    /**
     * Set filesize
     *
     * @param string $filesize
     * @return Photos
     */
    public function setFilesize($filesize)
    {
        $this->filesize = $filesize;
    
        return $this;
    }

    /**
     * Get filesize
     *
     * @return string 
     */
    public function getFilesize()
    {
        return $this->filesize;
    }

    /**
     * Set filetype
     *
     * @param string $filetype
     * @return Photos
     */
    public function setFiletype($filetype)
    {
        $this->filetype = $filetype;
    
        return $this;
    }

    /**
     * Get filetype
     *
     * @return string 
     */
    public function getFiletype()
    {
        return $this->filetype;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Photos
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    
        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set datepost
     *
     * @param \DateTime $datepost
     * @return Photos
     */
    public function setDatepost($datepost)
    {
        $this->datepost = $datepost;
    
        return $this;
    }

    /**
     * Get datepost
     *
     * @return \DateTime 
     */
    public function getDatepost()
    {
        return $this->datepost;
    }

    /**
     * Set uniqueId
     *
     * @param string $uniqueId
     * @return Photos
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

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}