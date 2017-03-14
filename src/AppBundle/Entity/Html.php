<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="html")
 */
class Html
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $projectId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $htmlFile;

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
     * Set projectId
     *
     * @param integer $projectId
     *
     * @return Selector
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;

        return $this;
    }

    /**
     * Get projectId
     *
     * @return integer
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Selector
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set htmlfile
     *
     * @param string $htmlfile
     *
     * @return htmlfile
     */
    public function setHtmlfile($htmlFile)
    {
        $this->htmlFile = $htmlFile;

        return $this;
    }

    /**
     * Get htmlfile
     *
     * @return string
     */
    public function getHtmlfile()
    {
        return $this->htmlFile;
    }
}
