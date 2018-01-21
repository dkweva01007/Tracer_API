<?php

namespace DB\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DB\UserBundle\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Friend
 *
 * @ORM\Table(name="API_Friend")
 * @ORM\Entity(repositoryClass="DB\ServiceBundle\Entity\DefaultRepository")
 * @ExclusionPolicy("all")
 */
class Friend {

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="DB\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user1", referencedColumnName="id")
     * })     
     * @Expose
     */
    private $idUser1;
    
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="DB\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user2", referencedColumnName="id")
     * })     
     * @Expose
     */
    private $idUser2;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     * @Expose
     */
    private $updated;
    
    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime", nullable=true)
     * @Expose
     */
    private $created;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     * @Expose
     */
    private $status;

    public function __construct() {
        $this->status = 0;
    }

    /**
     * Set idUser1
     *
     * @param \DB\UserBundle\Entity\User $idUser
     * @return Profile
     */
    public function setIdUser1(\DB\UserBundle\Entity\User $idUser = null) {
        $this->idUser1 = $idUser;

        return $this;
    }

    /**
     * Get idUser1
     *
     * @return \DB\UserBundle\Entity\User 
     */
    public function getIdUser1() {
        return $this->idUser1;
    }
    
    /**
     * Set idUser2
     *
     * @param \DB\UserBundle\Entity\User $idUser
     * @return Profile
     */
    public function setIdUser2(\DB\UserBundle\Entity\User $idUser = null) {
        $this->idUser2 = $idUser;

        return $this;
    }

    /**
     * Get idUser2
     *
     * @return \DB\UserBundle\Entity\User 
     */
    public function getIdUser2() {
        return $this->idUser2;
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
     * Set created
     *
     * @param \DateTime $created
     * @return Profile
     */
    public function setCreated($created) {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated() {
        return $this->created;
    }
    
    /**
     * Set status
     *
     * @param integer $status
     * @return Profile
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus() {
        return $this->status;
    }

}
