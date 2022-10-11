<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;


/**
 * User
 *
 * @ORM\Table(name="fos_user")
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @var \DateTime
     */
    protected $lastLogin;

    public function getlastLogin()
    {
        return $this->lastLogin;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function __construct()
    {
        parent::__construct();
        $this->notices = new ArrayCollection();
        $this->roles = ["ROLE_USER"];
    }

    // Relations

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Notice", mappedBy="user", cascade={"remove"})
     */
    private $notices;

    /**
     * Add notice.
     *
     * @param \AppBundle\Entity\Notice $notice
     *
     * @return User
     */
    public function addNotice(\AppBundle\Entity\Notice $notice)
    {
        $this->notices[] = $notice;

        return $this;
    }

    /**
     * Remove notice.
     *
     * @param \AppBundle\Entity\Notice $notice
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeNotice(\AppBundle\Entity\Notice $notice)
    {
        return $this->notices->removeElement($notice);
    }

    /**
     * Get notices.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotices()
    {
        return $this->notices;
    }

}
