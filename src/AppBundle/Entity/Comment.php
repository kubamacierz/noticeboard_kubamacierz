<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="comment_text", type="text")
     */
    private $commentText;

    /**
     * @var
     * @ORM\Column(name="creation_date", type="date", nullable=true)
     */
    private $creationDate;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set commentText.
     *
     * @param string $commentText
     *
     * @return Comment
     */
    public function setCommentText($commentText)
    {
        $this->commentText = $commentText;

        return $this;
    }

    /**
     * Get commentText.
     *
     * @return string
     */
    public function getCommentText()
    {
        return $this->commentText;
    }

    // Relations

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="Notice", inversedBy="comments")
     * @ORM\JoinColumn(name="notice_id", referencedColumnName="id")
     */
    private $notice;

    /**
     * Set notice.
     *
     * @param \AppBundle\Entity\Notice|null $notice
     *
     * @return Comment
     */
    public function setNotice(\AppBundle\Entity\Notice $notice = null)
    {
        $this->notice = $notice;

        return $this;
    }

    /**
     * Get notice.
     *
     * @return \AppBundle\Entity\Notice|null
     */
    public function getNotice()
    {
        return $this->notice;
    }

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="User", inversedBy="comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * Set user.
     *
     * @param User|null $user
     *
     * @return Comment
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    public function getUserId()
    {
        $this->getUser()->getId();
        return $this;
    }

    /**
     * Set creationDate.
     *
     * @param \DateTime|null $creationDate
     *
     * @return Comment
     */
    public function setCreationDate($creationDate = null)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate.
     *
     * @return \DateTime|null
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function __construct()
    {
        $this->creationDate = new \DateTime();
    }
}
