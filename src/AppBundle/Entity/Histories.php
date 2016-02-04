<?php

namespace AppBundle\Entity;

/**
 * Histories
 */
class Histories
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $evTime;

    /**
     * @var \AppBundle\Entity\Events
     */
    private $ev;


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
     * Set evTime
     *
     * @param \DateTime $evTime
     *
     * @return Histories
     */
    public function setEvTime($evTime)
    {
        $this->evTime = $evTime;

        return $this;
    }

    /**
     * Get evTime
     *
     * @return \DateTime
     */
    public function getEvTime()
    {
        return $this->evTime;
    }

    /**
     * Set ev
     *
     * @param \AppBundle\Entity\Events $ev
     *
     * @return Histories
     */
    public function setEv(\AppBundle\Entity\Events $ev = null)
    {
        $this->ev = $ev;

        return $this;
    }

    /**
     * Get ev
     *
     * @return \AppBundle\Entity\Events
     */
    public function getEv()
    {
        return $this->ev;
    }

    public function __construct() {
        $this->evTime = new \DateTime("now", new \DateTimeZone("Europe/Moscow"));
    }
}

