<?php

namespace Paradise\PhotoAppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comments
 *
 * @ORM\Table(name="comments")
 * @ORM\Entity
 */
class Comments
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
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=false)
     */
    private $comment;

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
     * Set comment
     *
     * @param string $comment
     * @return Comments
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    
        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Comments
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
     * @return Comments
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
     * @return Comments
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
     * @return Comments
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