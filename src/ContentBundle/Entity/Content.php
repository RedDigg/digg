<?php

namespace ContentBundle\Entity;

use ChannelBundle\Entity\Channel;
use CoreBundle\Traits\Bleamable;
use CoreBundle\Traits\Timestampable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="ContentBundle\Entity\Repository\ContentRepository")
 * @ORM\Table()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ExclusionPolicy("all")
 */
class Content
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

    /**
     * [min 5 chars, max 150]
     *
     * @ORM\Column(type="string", nullable=false)
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     *
     * @Assert\NotBlank(message = "Field 'title' should not be blank.")
     * @Assert\Length(
     *      min = 5,
     *      max = 150,
     *      minMessage = "Field 'title' should be at least {{ limit }} characters long",
     *      maxMessage = "Field 'title' cannot be longer than {{ limit }} characters"
     * )
     */
    private $title;

    /**
     * [min 0 chars, max 255]
     *
     * @ORM\Column(type="string", nullable=true)
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     *
     * @Assert\Length(
     *      max = 255,
     *      maxMessage = "Field 'description' cannot be longer than {{ limit }} characters"
     * )
     */
    private $description;

    /**
     *
     * @ORM\OneToMany(targetEntity="ContentBundle\Entity\ContentRelated", mappedBy="content")
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     * */
    private $relatedContent;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Expose
     * @JMS\Groups({"mod","admin"})
     */
    private $domain;

    /**
     * Number of comments
     *
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     */
    private $commentsCount = 0;

    /**
     * TRUE if content is in english language
     *
     * @ORM\Column(type="boolean", options={"unsigned"=true})
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     */
    private $eng = false;

    /**
     * TRUE if content is NotSafeForWork (+18)
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
     *
     * @var User
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    protected $createdBy;

    /**
     * User ID which updates this content
     *
     * @var User
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="updated_by", referencedColumnName="id")
     */
    protected $updatedBy;

    /**
     * Channels list
     *
     * @Assert\NotBlank(message = "Field 'channels' should not be blank.")
     *
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     * @ORM\ManyToMany(targetEntity="ChannelBundle\Entity\Channel")
     * @ORM\JoinTable(name="content_channels")
     */
    protected $channels;

    /**
     * Content comments
     *
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     * @ORM\OneToMany(targetEntity="ContentBundle\Entity\ContentComment", mappedBy="content")
     */
    protected $comments;

    /**
     * DateTime of deletion. (For PowerUsers only)
     *
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     * @Expose
     * @JMS\Groups({"mod","admin"})
     */
    protected $deletedAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->relatedContent = new \Doctrine\Common\Collections\ArrayCollection();
        $this->channels = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Sets deletedAt.
     *
     * @param \Datetime|null $deletedAt
     *
     * @return $this
     */
    public function setDeletedAt(\DateTime $deletedAt = null)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Returns deletedAt.
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Is deleted?
     *
     * @return bool
     *
     * @JMS\VirtualProperty
     * @JMS\SerializedName("deleted")
     * @JMS\Groups({"mod","admin"})
     */
    public function isDeleted()
    {
        return null !== $this->deletedAt;
    }

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
     * @return Content
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
     * Set description
     *
     * @param string $description
     *
     * @return Content
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set domain
     *
     * @param string $domain
     *
     * @return Content
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set commentsCount
     *
     * @param integer $commentsCount
     *
     * @return Content
     */
    public function setCommentsCount($commentsCount)
    {
        $this->commentsCount = $commentsCount;

        return $this;
    }

    /**
     * Get commentsCount
     *
     * @return integer
     */
    public function getCommentsCount()
    {
        return $this->commentsCount;
    }

    /**
     * Set eng
     *
     * @param boolean $eng
     *
     * @return Content
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
     * @return Content
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
     * @return Content
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
     * @return Content
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
     * @return Content
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
     * Add relatedContent
     *
     * @param \ContentBundle\Entity\ContentRelated $relatedContent
     *
     * @return Content
     */
    public function addRelatedContent(\ContentBundle\Entity\ContentRelated $relatedContent)
    {
        $this->relatedContent[] = $relatedContent;

        return $this;
    }

    /**
     * Remove relatedContent
     *
     * @param \ContentBundle\Entity\ContentRelated $relatedContent
     */
    public function removeRelatedContent(\ContentBundle\Entity\ContentRelated $relatedContent)
    {
        $this->relatedContent->removeElement($relatedContent);
    }

    /**
     * Get relatedContent
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRelatedContent()
    {
        return $this->relatedContent;
    }

    /**
     * Set createdBy
     *
     * @param \UserBundle\Entity\User $createdBy
     *
     * @return Content
     */
    public function setCreatedBy(\UserBundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \UserBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set updatedBy
     *
     * @param \UserBundle\Entity\User $updatedBy
     *
     * @return Content
     */
    public function setUpdatedBy(\UserBundle\Entity\User $updatedBy = null)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return \UserBundle\Entity\User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Add channel
     *
     * @param \ChannelBundle\Entity\Channel $channel
     *
     * @return Content
     */
    public function addChannel(\ChannelBundle\Entity\Channel $channel)
    {
        $this->channels[] = $channel;

        return $this;
    }

    /**
     * Remove channel
     *
     * @param \ChannelBundle\Entity\Channel $channel
     */
    public function removeChannel(\ChannelBundle\Entity\Channel $channel)
    {
        $this->channels->removeElement($channel);
    }

    /**
     * Get channels
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChannels()
    {
        return $this->channels;
    }

    /**
     * Add comment
     *
     * @param \ContentBundle\Entity\ContentComment $comment
     *
     * @return Content
     */
    public function addComment(\ContentBundle\Entity\ContentComment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \ContentBundle\Entity\ContentComment $comment
     */
    public function removeComment(\ContentBundle\Entity\ContentComment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }
}
