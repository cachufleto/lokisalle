<?php

namespace Sami\Bundle\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Articles
 */
class Articles
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $body;

    /**
     * @var \DateTime
     */
    private $published;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Sami\Bundle\BlogBundle\Entity\Users
     */
    private $user;


    /**
     * Set title
     *
     * @param string $title
     * @return Articles
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
     * Set body
     *
     * @param string $body
     * @return Articles
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set published
     *
     * @param \DateTime $published
     * @return Articles
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return \DateTime 
     */
    public function getPublished()
    {
        return $this->published;
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
     * Set user
     *
     * @param \Sami\Bundle\BlogBundle\Entity\Users $user
     * @return Articles
     */
    public function setUser(\Sami\Bundle\BlogBundle\Entity\Users $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Sami\Bundle\BlogBundle\Entity\Users 
     */
    public function getUser()
    {
        return $this->user;
    }
}
