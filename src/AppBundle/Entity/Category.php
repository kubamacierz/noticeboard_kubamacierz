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
     * @ORM\Column(name="categoty_name", type="string", length=255)
     */
    private $categotyName;


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
     * Set categotyName.
     *
     * @param string $categotyName
     *
     * @return Category
     */
    public function setCategotyName($categotyName)
    {
        $this->categotyName = $categotyName;

        return $this;
    }

    /**
     * Get categotyName.
     *
     * @return string
     */
    public function getCategotyName()
    {
        return $this->categotyName;
    }
}
