<?php

namespace Paradise\PhotoAppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Events
 *
 * @ORM\Table(name="events")
 * @ORM\Entity
 */
class Events
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
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="desc", type="text", nullable=true)
     */
    private $desc;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="eventdate", type="date", nullable=true)
     */
    private $eventdate;

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
     * Set title
     *
     * @param string $title
     * @return Events
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
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
     * @return Events
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;
    
        return $this;
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
     * Set eventdate
     *
     * @param \DateTime $eventdate
     * @return Events
     */
    public function setEventdate($eventdate)
    {
        $this->eventdate = $eventdate;
    
        return $this;
    }

    /**
     * Get eventdate
     *
     * @return \DateTime 
     */
    public function getEventdate()
    {
        return $this->eventdate;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Events
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
     * @return Events
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