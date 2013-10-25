<?php

namespace Paradise\PhotoAppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tags
 *
 * @ORM\Table(name="tags")
 * @ORM\Entity
 */
class Tags
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
     * @ORM\Column(name="tagref_id", type="integer", nullable=false)
     */
    private $tagrefId;

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
     * @var \Photos
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Photos")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="photo_id", referencedColumnName="id")
     * })
     */
    private $photo;

    /**
     * @var \Tagtype
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Tagtype")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tagtype_id", referencedColumnName="id")
     * })
     */
    private $tagtype;



    /**
     * Set tagrefId
     *
     * @param integer $tagrefId
     * @return Tags
     */
    public function setTagrefId($tagrefId)
    {
        $this->tagrefId = $tagrefId;
    
        return $this;
    }

    /**
     * Get tagrefId
     *
     * @return integer 
     */
    public function getTagrefId()
    {
        return $this->tagrefId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Tags
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
     * @return Tags
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
     * @return Tags
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
     * Set tagtype
     *
     * @param \Paradise\PhotoAppBundle\Entity\Tagtype $tagtype
     * @return Tags
     */
    public function setTagtype(\Paradise\PhotoAppBundle\Entity\Tagtype $tagtype = null)
    {
        $this->tagtype = $tagtype;
    
        return $this;
    }

    /**
     * Get tagtype
     *
     * @return \Paradise\PhotoAppBundle\Entity\Tagtype 
     */
    public function getTagtype()
    {
        return $this->tagtype;
    }

    /**
     * Set photo
     *
     * @param \Paradise\PhotoAppBundle\Entity\Photos $photo
     * @return Tags
     */
    public function setPhoto(\Paradise\PhotoAppBundle\Entity\Photos $photo = null)
    {
        $this->photo = $photo;
    
        return $this;
    }

    /**
     * Get photo
     *
     * @return \Paradise\PhotoAppBundle\Entity\Photos 
     */
    public function getPhoto()
    {
        return $this->photo;
    }
}