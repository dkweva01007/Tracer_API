<?php

namespace DB\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * CurrentMission
 *
 * @ORM\Table(name="API_current_mission")
 * @ORM\Entity(repositoryClass="DB\ServiceBundle\Entity\DefaultRepository")
 * @ExclusionPolicy("all")
 */
class CurrentMission {

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
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })     
     * @Expose
     */
    private $idUser;
    
     /**
     * @var integer
     *
     * @ORM\OneToOne(targetEntity="Mission")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_Mission", referencedColumnName="id")
     * })     
     * @Expose
     */
    private $idMission;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     * @Expose
     */
    private $status;
    
    
    /**
     * @var integer
     *
     * @ORM\Column(name="distance_make", type="float", precision=3, scale=0, nullable=false)
     * @Expose
     */
    private $distanceMake;

    

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

    public function getIdMission() {
        return $this->idMission;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getDistanceMake() {
        return $this->distanceMake;
    }

    public function setIdUser($idUser) {
        $this->idUser = $idUser;
    }

    public function setIdMission($idMission) {
        $this->idMission = $idMission;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setDistanceMake($distanceMake) {
        $this->distanceMake = $distanceMake;
    }


    
}
