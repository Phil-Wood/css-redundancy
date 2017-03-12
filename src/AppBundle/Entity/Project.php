<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="project")
 */
class Project
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pages = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $selectors = 0;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\NotBlank(message="Please, upload a CSS file.")
     * @Assert\File(mimeTypes={ "text/*" })
     */
    private $cssfile = null;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set pages
     *
     * @param string $pages
     *
     * @return Project
     */
    public function setPages($pages)
    {
        $this->pages = $pages;

        return $this;
    }

    /**
     * Get pages
     *
     * @return string
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Set selectors
     *
     * @param string $selectors
     *
     * @return Project
     */
    public function setSelectors($selectors)
    {
        $this->selectors = $selectors;

        return $this;
    }

    /**
     * Get selectors
     *
     * @return string
     */
    public function getSelectors()
    {
        return $this->selectors;
    }

    /**
     * Set cssfile
     *
     * @param string $cssfile
     *
     * @return Project
     */
    public function setCssFile($cssfile)
    {
        $this->cssfile = $cssfile;

        return $this;
    }

    /**
     * Get cssfile
     *
     * @return string
     */
    public function getCssFile()
    {
        return $this->cssfile;
    }
}
