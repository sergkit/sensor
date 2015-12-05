<?php
namespace AppBundle\Entity;
//$ php app/console doctrine:generate:entities AppBundle/Entity/Product генерация геттеров и сеттеров
//php app/console doctrine:schema:update --force генерация таблиц
//
// php app/console doctrine:mapping:import Имя_бандла формат_конфига   генерация модели из базы
//Таким образом мы создадим конфиг для построения модели. Формат может быть разным, например, yml, xml и т.д. После этого создадим ORM:
//php app/console doctrine:generate:entities Имя_бандла
//https://www.skipper18.com/en/download  визуальное проектирование базы
//http://www.symfony2cheatsheet.com/
namespace AppBundle\Entity;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NotBlank;



/*
 * Сервер домашней автоматизации.
 * author kitserg68@gmail.com
 *
 */



/**
 * Description of Product
 *
 * @author benjuchis
 */
use Doctrine\ORM\Mapping as ORM;

/**
 * @Assert\Callback(methods={"isSumOk"})
 * @ORM\Entity
 * @ORM\Table(name="thtable")
 *        //ORM\Entity(repositoryClass="AppBundle\Entity\ThtableRepository")
 */
class Thtable {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @ORM\Column(type="integer")
     */
    protected $room;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $co2;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $t;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $h;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $VOC;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $VOCR;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $VOCold;
    protected $CheckFields;

    public function __construct() {
        $this->date = new \DateTime("now", new \DateTimeZone("Europe/Moscow"));
        $this->room = 1;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Thtable
     */
    public function setDate(\DateTime $date) {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set room
     *
     * @param integer $room
     *
     * @return Thtable
     */
    public function setRoom($room) {
        $this->room = $room;

        return $this;
    }

    /**
     * Get room
     *
     * @return integer
     */
    public function getRoom() {
        return $this->room;
    }

    /**
     * Set co2
     *
     * @param string $co2
     *
     * @return Thtable
     */
    public function setCo2($co2) {
        $this->co2 = $co2;

        return $this;
    }

    /**
     * Get co2
     *
     * @return string
     */
    public function getCo2() {
        return $this->co2;
    }

    /**
     * Set t
     *
     * @param string $t
     *
     * @return Thtable
     */
    public function setT($t) {
        $this->t = $t;

        return $this;
    }

    /**
     * Get t
     *
     * @return string
     */
    public function getT() {
        return $this->t;
    }

    /**
     * Set h
     *
     * @param string $h
     *
     * @return Thtable
     */
    public function setH($h) {
        $this->h = $h;

        return $this;
    }

    /**
     * Get h
     *
     * @return string
     */
    public function getH() {
        return $this->h;
    }

    /**
     * Set vOC
     *
     * @param string $vOC
     *
     * @return Thtable
     */
    public function setVOC($vOC) {
        $this->VOC = $vOC;

        return $this;
    }

    /**
     * Get vOC
     *
     * @return string
     */
    public function getVOC() {
        return $this->VOC;
    }

    /**
     * Set vOCR
     *
     * @param string $vOCR
     *
     * @return Thtable
     */
    public function setVOCR($vOCR) {
        $this->VOCR = $vOCR;

        return $this;
    }

    /**
     * Get vOCR
     *
     * @return string
     */
    public function getVOCR() {
        return $this->VOCR;
    }

    /**
     * Set vOCold
     *
     * @param string $vOCold
     *
     * @return Thtable
     */
    public function setVOCold($vOCold) {
        $this->VOCold = $vOCold;

        return $this;
    }

    /**
     * Get vOCold
     *
     * @return string
     */
    public function getVOCold() {
        return $this->VOCold;
    }

    public function setCheckFields($CheckFields) {
        $this->CheckFields = $CheckFields;

        return $this;
    }

    public function getCheckFields() {
        return $this->CheckFields;
    }
    /**
     * @Assert\True(message = "Контрольное поле не корректно")
     */
    public function isSumOk()
    {
        return ($this->getCo2()+$this->getH()+$this->getT()+$this->getVOC()==$this->getCheckFields());
    }

}
