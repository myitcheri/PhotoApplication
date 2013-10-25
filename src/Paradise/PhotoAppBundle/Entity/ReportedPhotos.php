<?php

namespace Paradise\PhotoAppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReportedPhotos
 *
 * @ORM\Table(name="reported_photos")
 * @ORM\Entity
 */
class ReportedPhotos
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
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="photo_id", type="integer", nullable=false)
     */
    private $photoId;

    /**
     * @var string
     *
     * @ORM\Column(name="reason", type="string", length=255, nullable=false)
     */
    private $reason;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint", nullable=false)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datepost", type="datetime", nullable=false)
     */
    private $datepost;



    /**
     * Set userId
     *
     * @param integer $userId
     * @return ReportedPhotos
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
     * Set photoId
     *
     * @param integer $photoId
     * @return ReportedPhotos
     */
    public function setPhotoId($photoId)
    {
        $this->photoId = $photoId;
    
        return $this;
    }

    /**
     * Get photoId
     *
     * @return integer 
     */
    public function getPhotoId()
    {
        return $this->photoId;
    }

    /**
     * Set reason
     *
     * @param string $reason
     * @return ReportedPhotos
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
    
        return $this;
    }

    /**
     * Get reason
     *
     * @return string 
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return ReportedPhotos
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set datepost
     *
     * @param \DateTime $datepost
     * @return ReportedPhotos
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}