<?php

namespace AppBundle\Entity;

/**
 * Events
 */
class Events
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $tmin;
    /**
     * @var string
     */
    private $tmax;
    /**
     * @var string
     */
    private $hmin;

    /**
     * @var string
     */
    private $co2max;

    /**
     * @var string
     */
    private $vocmax;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \AppBundle\Entity\Rooms
     */
    private $room;

    /**
     * @var \AppBundle\Entity\Users
     */
    private $userid;


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
     * Set tmin
     *
     * @param string $tmin
     *
     * @return Events
     */
    public function setTmin($tmin)
    {
        $this->tmin = $tmin;

        return $this;
    }

    /**
     * Get tmin
     *
     * @return string
     */
    public function getTmin()
    {
        return $this->tmin;
    }
    /**
     * Set tmax
     *
     * @param string $tmax
     *
     * @return Events
     */
    public function setTmax($tmax)
    {
        $this->tmax = $tmax;

        return $this;
    }

    /**
     * Get tmax
     *
     * @return string
     */
    public function getTmax()
    {
        return $this->tmax;
    }

    /**
     * Set hmin
     *
     * @param string $hmin
     *
     * @return Events
     */
    public function setHmin($hmin)
    {
        $this->hmin = $hmin;

        return $this;
    }

    /**
     * Get hmin
     *
     * @return string
     */
    public function getHmin()
    {
        return $this->hmin;
    }

    /**
     * Set co2max
     *
     * @param string $co2max
     *
     * @return Events
     */
    public function setCo2max($co2max)
    {
        $this->co2max = $co2max;

        return $this;
    }

    /**
     * Get co2max
     *
     * @return string
     */
    public function getCo2max()
    {
        return $this->co2max;
    }

    /**
     * Set vocmax
     *
     * @param string $vocmax
     *
     * @return Events
     */
    public function setVocmax($vocmax)
    {
        $this->vocmax = $vocmax;

        return $this;
    }

    /**
     * Get vocmax
     *
     * @return string
     */
    public function getVocmax()
    {
        return $this->vocmax;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Events
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set room
     *
     * @param \AppBundle\Entity\Rooms $room
     *
     * @return Events
     */
    public function setRoom(\AppBundle\Entity\Rooms $room = null)
    {
        $this->room = $room;

        return $this;
    }

    /**
     * Get room
     *
     * @return \AppBundle\Entity\Rooms
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * Set userid
     *
     * @param \AppBundle\Entity\Users $userid
     *
     * @return Events
     */
    public function setUserid(\AppBundle\Entity\Users $userid = null)
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Get userid
     *
     * @return \AppBundle\Entity\Users
     */
    public function getUserid()
    {
        return $this->userid;
    }
}
