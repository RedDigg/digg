<?php

namespace ContentBundle\Entity;

use CoreBundle\Traits\Bleamable;
use CoreBundle\Traits\Timestampable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ExclusionPolicy("all")
 */
class ContentRelated
{
    /**
     * Hook SoftDeleteable behavior
     * updates deletedAt field
     */
    use SoftDeleteableEntity;
    use Timestampable;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     */
    protected $id;

    public function __construct()
    {
    }

    /**
     * [min 3 chars, max 50]
     *
     * @ORM\Column(type="string", nullable=false)
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     *
     * @Assert\NotBlank(message = "Field 'title' should not be blank.")
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "Field 'title' should be at least {{ limit }} characters long",
     *      maxMessage = "Field 'title' cannot be longer than {{ limit }} characters"
     * )
     */
    private $title;

    /**
     * URL of related content
     *
     * @ORM\Column(type="string", nullable=true)
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     *
     * @Assert\Url()
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="ContentBundle\Entity\Content", inversedBy="relatedContent")
     * @ORM\JoinColumn(name="content", referencedColumnName="id")
     */
    private $content;

    /**
     * TRUE if is in english language
     *
     * @ORM\Column(type="boolean", options={"unsigned"=true})
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     */
    private $eng = false;

    /**
     * TRUE if is NotSafeForWork (+18)
     *
     * @ORM\Column(type="boolean", options={"unsigned"=true})
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     */
    private $nsfw = false;

    /**
     * Number of up votes
     *
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     */
    private $uv = 0;

    /**
     * Number of down votes
     *
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     */
    private $dv = 0;

    /**
     * Sum of votes
     *
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     */
    private $score = 0;

    /**
     * User ID which creates this content
     * @var User
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     */
    protected $createdBy;

    /**
     * User ID which updates this content
     * @var User
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="updated_by", referencedColumnName="id")
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     */
    protected $updatedBy;

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
     * Set title
     *
     * @param string $title
     *
     * @return ContentRelated
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return ContentRelated
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
     * Set eng
     *
     * @param boolean $eng
     *
     * @return ContentRelated
     */
    public function setEng($eng)
    {
        $this->eng = $eng;

        return $this;
    }

    /**
     * Get eng
     *
     * @return boolean
     */
    public function getEng()
    {
        return $this->eng;
    }

    /**
     * Set nsfw
     *
     * @param boolean $nsfw
     *
     * @return ContentRelated
     */
    public function setNsfw($nsfw)
    {
        $this->nsfw = $nsfw;

        return $this;
    }

    /**
     * Get nsfw
     *
     * @return boolean
     */
    public function getNsfw()
    {
        return $this->nsfw;
    }

    /**
     * Set uv
     *
     * @param integer $uv
     *
     * @return ContentRelated
     */
    public function setUv($uv)
    {
        $this->uv = $uv;

        return $this;
    }

    /**
     * Get uv
     *
     * @return integer
     */
    public function getUv()
    {
        return $this->uv;
    }

    /**
     * Set dv
     *
     * @param integer $dv
     *
     * @return ContentRelated
     */
    public function setDv($dv)
    {
        $this->dv = $dv;

        return $this;
    }

    /**
     * Get dv
     *
     * @return integer
     */
    public function getDv()
    {
        return $this->dv;
    }

    /**
     * Set score
     *
     * @param integer $score
     *
     * @return ContentRelated
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set content
     *
     * @param \ContentBundle\Entity\Content $content
     *
     * @return ContentRelated
     */
    public function setContent(\ContentBundle\Entity\Content $content = null)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return \ContentBundle\Entity\Content
     */
    public function getContent()
    {
        return $this->content;
    }
}
