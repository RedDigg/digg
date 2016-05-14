<?php
/**
 * Created by PhpStorm.
 * User: lmalicki
 * Date: 03.05.16
 * Time: 21:35
 */

namespace ContentBundle\Entity;

use CoreBundle\Traits\Timestampable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ExclusionPolicy("all")
 */
class ContentComment
{
    /**
     * Hook SoftDeleteable behavior
     * updates deletedAt field
     */
    use Timestampable;


    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     */
    protected $id;

    // TODO: Show body in the API DOCS. Now it is not visible, due to missing @JMS\Groups
    /**
     * Comment body. Null if comment is deleted.
     *
     * @ORM\Column(type="text")
     * @Expose
     */
    protected $body;

    /**
     * @ORM\ManyToOne(targetEntity="ContentBundle\Entity\Content", inversedBy="comments", cascade={"persist"})
     * @ORM\JoinColumn(name="content", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $content;

    /**
     * @ORM\ManyToOne(targetEntity="ContentComment", inversedBy="children", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="ContentComment", mappedBy="parent")
     */
    protected $children;

    /**
     * User ID which creates this content
     *
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
     * User ID which updates this comment
     *
     * @var User
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="updated_by", referencedColumnName="id")
     * @Expose
     * @JMS\Groups({"mod","admin"})
     */
    protected $updatedBy;

    /**
     * DateTime of deletion. (For PowerUsers only)
     *
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     * @Expose
     * @JMS\Groups({"mod","admin"})
     */
    protected $deletedAt;

//    /**
//     * @ORM\ManyToMany(targetEntity="Vote", cascade={"all"}, inversedBy="commentUpvotes")
//     * @ORM\JoinTable(name="comment_upvotes")
//     */
//    private $upvotes;
//    /**
//     * @ORM\ManyToMany(targetEntity="Vote", cascade={"all"}, inversedBy="commentDownvotes")
//     * @ORM\JoinTable(name="comment_downvotes")
//     */
//    private $downvotes;
//    /**
//     * @ORM\Column(name="total_upvotes", type="integer", options={"default"=0}, nullable=false)
//     */
//    private $totalUpvotes;
//    /**
//     * @ORM\Column(name="total_downvotes", type="integer", options={"default"=0}, nullable=false)
//     */
//    private $totalDownvotes;


    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("parent")
     * @JMS\Groups({"user","mod","admin"})
     */
    public function virtualParent()
    {
        if ($this->parent) {
            return $this->getParent()->getId();
        }

        return null;
    }

    /**
     *
     * @JMS\VirtualProperty
     * @JMS\SerializedName("body")
     * @JMS\Groups({"user"})
     */
    public function virtualBodyUser()
    {
        if ($this->deletedAt) {
            return null;
        }

        return $this->body;
    }

    /**
     * @JMS\VirtualProperty
     * @JMS\SerializedName("body")
     * @JMS\Groups({"mod","admin"})
     */
    public function virtualBodyPowerUser()
    {
        return $this->body;
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
     * @JMS\Groups({"user","mod","admin"})
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
     * Set body
     *
     * @param string $body
     *
     * @return ContentComment
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set parent
     *
     * @param \ContentBundle\Entity\ContentComment $parent
     *
     * @return ContentComment
     */
    public function setParent(\ContentBundle\Entity\ContentComment $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \ContentBundle\Entity\ContentComment
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child
     *
     * @param \ContentBundle\Entity\ContentComment $child
     *
     * @return ContentComment
     */
    public function addChild(\ContentBundle\Entity\ContentComment $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \ContentBundle\Entity\ContentComment $child
     */
    public function removeChild(\ContentBundle\Entity\ContentComment $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set createdBy
     *
     * @param \UserBundle\Entity\User $createdBy
     *
     * @return ContentComment
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
     * @return ContentComment
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
     * Set content
     *
     * @param \ContentBundle\Entity\Content $content
     *
     * @return ContentComment
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
