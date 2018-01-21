<?php

namespace DB\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Mission
 *
 * @ORM\Table(name="API_mission")
 * @ORM\Entity(repositoryClass="DB\ServiceBundle\Entity\DefaultRepository")
 * @ExclusionPolicy("all")
 */
class Mission {

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
     * @var integer
     *
     * @ORM\Column(name="type", type="integer", nullable=false)
     * @Expose
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="special", type="integer", nullable=true)
     * @Expose
     */
    private $special;

    /**
     * @var integer
     *
     * @ORM\Column(name="distance", type="float", precision=3, scale=0, nullable=false)
     * @Expose
     */
    private $distance;

    public function __construct() {
        $this->type = 1;
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
    
     /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

     /**
     * Get distance
     *
     * @return int 
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Get special
     *
     * @return int 
     */
    public function getSpecial() {
        return $this->special;
    }

    /**
     * Get special
     *
     * @return int 
     */
    public function setSpecial($special) {
        $this->special = $special;
    }

        
    /**
     * Set distance
     *
     * @param float $name
     * @return Mission
     */
    public function setName($name) {
        $this->name = $name;
    }
    
    /**
     * Set distance
     *
     * @param float $type
     * @return Mission
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * Set distance
     *
     * @param float $distance
     * @return Mission
     */
    public function setDistance($distance) {
        $this->distance = $distance;

        return $this;
    }

    /**
     * Get distance
     *
     * @return float 
     */
    public function getDistance() {
        return $this->distance;
    }

}
