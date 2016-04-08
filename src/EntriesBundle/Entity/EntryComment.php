<?php

namespace EntriesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation as JMS;
use CoreBundle\Traits\Timestampable;

/**
 * @ORM\Entity
 * @ORM\Table()
 * @ExclusionPolicy("all")
 */
class EntryComment
{

    /**
     * Hook SoftDeleteable behavior
     * updates deletedAt field
     */
    use SoftDeleteableEntity;
    use BlameableEntity;
    use Timestampable;

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="author", referencedColumnName="id")
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     */
    protected $author;

    /**
     * @ORM\ManyToOne(targetEntity="EntriesBundle\Entity\Entry")
     * @ORM\JoinColumn(name="thread", referencedColumnName="id")
     *
     */
    protected $thread;


    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     */
    private $uv;

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     */
    private $dv;

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     */
    private $voteCount;


    /**
     * @ORM\Column(type="string")
     * @JMS\Groups({"admin"})
     */
    private $publicIP;

    /**
     * @ORM\Column(type="string")
     * @JMS\Groups({"admin"})
     */
    private $privateIP;

    /**
     * @ORM\OneToMany(targetEntity="EntriesBundle\Entity\EntryVoters", mappedBy="entry")
     *
     */
    private $voters;

    /**
     * @ORM\Column(type="integer")
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     * @var int
     */
    protected $score = 0;

    /**
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     */
    protected $body;

    /**
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     */
    protected $createdAt;

    /**
     * @Expose
     * @JMS\Groups({"mod","admin"})
     */
    protected $updatedAt;

    /**
     * @Expose
     * @JMS\Groups({"mod","admin"})
     */
    protected $deletedAt;

    /**
     * Sets the score of the comment.
     *
     * @param integer $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }

    /**
     * Returns the current score of the comment.
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Increments the comment score by the provided
     * value.
     *
     * @param integer value
     *
     * @return integer The new comment score
     */
    public function incrementScore($by = 1)
    {
        $this->score += $by;
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
     * Set uv
     *
     * @param integer $uv
     *
     * @return EntryComment
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
     * @return EntryComment
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
     * Set voteCount
     *
     * @param integer $voteCount
     *
     * @return EntryComment
     */
    public function setVoteCount($voteCount)
    {
        $this->voteCount = $voteCount;

        return $this;
    }

    /**
     * Get voteCount
     *
     * @return integer
     */
    public function getVoteCount()
    {
        return $this->voteCount;
    }

    /**
     * Set publicIP
     *
     * @param string $publicIP
     *
     * @return EntryComment
     */
    public function setPublicIP($publicIP)
    {
        $this->publicIP = $publicIP;

        return $this;
    }

    /**
     * Get publicIP
     *
     * @return string
     */
    public function getPublicIP()
    {
        return $this->publicIP;
    }

    /**
     * Set privateIP
     *
     * @param string $privateIP
     *
     * @return EntryComment
     */
    public function setPrivateIP($privateIP)
    {
        $this->privateIP = $privateIP;

        return $this;
    }

    /**
     * Get privateIP
     *
     * @return string
     */
    public function getPrivateIP()
    {
        return $this->privateIP;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->voters = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add voter
     *
     * @param \EntriesBundle\Entity\EntryVoters $voter
     *
     * @return EntryComment
     */
    public function addVoter(\EntriesBundle\Entity\EntryVoters $voter)
    {
        $this->voters[] = $voter;

        return $this;
    }

    /**
     * Remove voter
     *
     * @param \EntriesBundle\Entity\EntryVoters $voter
     */
    public function removeVoter(\EntriesBundle\Entity\EntryVoters $voter)
    {
        $this->voters->removeElement($voter);
    }

    /**
     * Get voters
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVoters()
    {
        return $this->voters;
    }

}
