<?php

namespace DB\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Profile
 *
 * @ORM\Table(name="API_Profile")
 * @ORM\Entity(repositoryClass="DB\ServiceBundle\Entity\DefaultRepository")
 * @ExclusionPolicy("all")
 */
class Profile {

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
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="change")
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     * @Expose
     */
    private $updated;

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
     * @ORM\Column(name="distance", type="float", precision=3, scale=0, nullable=false)
     * @Expose
     */
    private $distance;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="count_time", type="float", precision=2, scale=0, nullable=false)
     * @Expose
     */
    private $countTime;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="average", type="float", precision=2, scale=0, nullable=false)
     * @Expose
     */
    private $average ;

    /**
     * @var string
     *
     * @ORM\Column(name="count_mission_complete", type="integer", nullable=true)
     * @Expose
     */
    private $countMissionComplete;

    public function __construct() {
        $this->countMissionComplete = 0;
        $this->distance = 0.000;
        $this->average = 0;
        $this->countTime = 0;
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
     * Set updated
     *
     * @param \DateTime $updated
     * @return Profile
     */
    public function setUpdated($updated) {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated() {
        return $this->updated;
    }
    
    /**
     * Get countTime
     *
     * @return float 
     */
    public function getCountTime() {
        return $this->countTime;
    }

    /**
     * Get average
     *
     * @return float 
     */
    public function getAverage() {
        return $this->average;
    }

    /**
     * Set distance
     *
     * @param float $countTime
     * @return Profile
     */
    public function setCountTime($average) {
        $this->countTime = $countTime;
    }

    /**
     * Set average
     *
     * @param float $average
     * @return Profile
     */
    public function setAverage($average) {
        $this->average = $average;
    }

    
    /**
     * Set idUser
     *
     * @param \DB\UserBundle\Entity\User $idUser
     * @return Profile
     */
    public function setIdUser(\DB\UserBundle\Entity\User $idUser = null) {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return \DB\UserBundle\Entity\User 
     */
    public function getIdUser() {
        return $this->idUser;
    }

    /**
     * Set distance
     *
     * @param float $distance
     * @return Profile
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

    /**
     * Set countMissionComplete
     *
     * @param integer $countMissionComplete
     * @return Profile
     */
    public function setCountMissionComplete($countMissionComplete) {
        $this->countMissionComplete = $countMissionComplete;

        return $this;
    }

    /**
     * Get countMissionComplete
     *
     * @return integer 
     */
    public function getCountMissionComplete() {
        return $this->countMissionComplete;
    }

}
