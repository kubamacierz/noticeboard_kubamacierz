<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */
class Category
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
     * @ORM\Column(name="category_name", type="string", length=255)
     */
    private $categoryName;


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
     * Set categoryName.
     *
     * @param string $categoryName
     *
     * @return Category
     */
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    /**
     * Get categoryName.
     *
     * @return string
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    // Relations

//    /**
//     * @var
//     * @ORM\ManyToOne(targetEntity="Notice", inversedBy="categories")
//     * @ORM\JoinColumn(name="notice_id", referencedColumnName="id")
//     */
//    private $notice;

    /**
     * @var
     * @ORM\OneToMany(targetEntity="Notice", mappedBy="category")
     */
    private $notices;

    /**
     * Set notice.
     *
     * @param \AppBundle\Entity\Notice|null $notice
     *
     * @return Category
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
     * Constructor
     */
    public function __construct()
    {
        $this->notices = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add notice.
     *
     * @param \AppBundle\Entity\Notice $notice
     *
     * @return Category
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
