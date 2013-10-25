<?php

namespace Paradise\PhotoAppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tagtype
 *
 * @ORM\Table(name="tagtype")
 * @ORM\Entity
 */
class Tagtype
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
     * @ORM\Column(name="title", type="string", length=45, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=45, nullable=false)
     */
    private $code;



    /**
     * Set title
     *
     * @param string $title
     * @return Tagtype
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
     * Set code
     *
     * @param string $code
     * @return Tagtype
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
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