<?php

namespace DB\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * CurrentQuest
 *
 * @ORM\Table(name="API_current_quest")
 * @ORM\Entity(repositoryClass="DB\ServiceBundle\Entity\DefaultRepository")
 * @ExclusionPolicy("all")
 */
class CurrentQuest {

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
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="\DB\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })     
     * @Expose
     */
    private $idUser;
    
     /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Quest")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_Quest", referencedColumnName="id")
     * })     
     * @Expose
     */
    private $idQuest;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     * @Expose
     */
    private $status;
    
    
    /**
     * @var float
     *
     * @ORM\Column(name="distance_make", type="float", precision=3, scale=0, nullable=false)
     * @Expose
     */
    private $distanceMake;
    
    /**
     * @var float
     *
     * @ORM\Column(name="time_make", type="float", precision=3, scale=0, nullable=false)
     * @Expose
     */
    private $timeMake;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="special", type="integer", nullable=true)
     * @Expose
     */
    private $special;

    public function __construct() {
        $this->timeMake = 0.0;
        $this->distanceMake = 0.000;
        $this->status = 0;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }
    
    public function getIdUser() {
        return $this->idUser;
    }

    public function getIdQuest() {
        return $this->idQuest;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getDistanceMake() {
        return $this->distanceMake;
    }
    
    public function getTimeMake() {
        return $this->timeMake;
    }

    public function getSpecial() {
        return $this->special;
    }
    
    public function setIdUser(\DB\UserBundle\Entity\User $idUser) {
        $this->idUser = $idUser;
    }

    public function setIdQuest(\DB\ServiceBundle\Entity\Quest $idQuest) {
        $this->idQuest = $idQuest;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setDistanceMake($distanceMake) {
        $this->distanceMake = $distanceMake;
    }

    public function setTimeMake($timeMake) {
        $this->timeMake = $timeMake;
    }

    public function setSpecial($special) {
        $this->special = $special;
    }
    
}
