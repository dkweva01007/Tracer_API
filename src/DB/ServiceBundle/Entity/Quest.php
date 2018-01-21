<?php

namespace DB\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Quest
 *
 * @ORM\Table(name="API_quest")
 * @ORM\Entity(repositoryClass="DB\ServiceBundle\Entity\DefaultRepository")
 * @ExclusionPolicy("all")
 */
class Quest {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     * @Expose
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="time", type="float", precision=3, scale=0, nullable=false)
     * @Expose
     */
    private $time;

    /**
     * @var integer
     *
     * @ORM\Column(name="distance", type="float", precision=3, scale=0, nullable=false)
     * @Expose
     */
    private $distance;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="special", type="integer", nullable=true)
     * @Expose
     */
    private $special;

    public function __construct() {
        $this->time = 0;
        $this->distance = 0;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }
    
    public function getName() {
        return $this->name;
    }

    public function getTime() {
        return $this->time;
    }

    public function getDistance() {
        return $this->distance;
    }
    
    public function getSpecial() {
        return $this->special;
    }
    
    public function setName($name) {
        $this->name = $name;
    }

    public function setTime($time) {
        $this->time = $time;
    }

    public function setDistance($distance) {
        $this->distance = $distance;
    }
    
    public function setSpecial($special) {
        $this->special = $special;
    }

}
