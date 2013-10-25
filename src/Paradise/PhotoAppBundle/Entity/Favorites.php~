<?php

namespace Paradise\PhotoAppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Favorites
 *
 * @ORM\Table(name="favorites")
 * @ORM\Entity
 */
class Favorites
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
     * @var integer
     *
     * @ORM\Column(name="photo_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $photoId;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datepost", type="datetime", nullable=true)
     */
    private $datepost;



    /**
     * Set userId
     *
     * @param integer $userId
     * @return Favorites
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
     * @return Favorites
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
     * Set id
     *
     * @param integer $id
     * @return Favorites
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
     * Set photoId
     *
     * @param integer $photoId
     * @return Favorites
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
}