<?php

namespace Red\EntriesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\MaxDepth;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ExclusionPolicy("all")
 */
class Entry
{

    /**
     * Hook SoftDeleteable behavior
     * updates deletedAt field
     */
    use SoftDeleteableEntity;
    use BlameableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @JMS\Groups({"list"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Red\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=false)
     * @Expose
     * @MaxDepth(3)
     * @JMS\Groups({"list"})
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     * @Expose
     * @JMS\Groups({"list"})
     */
    private $text;

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @Expose
     * @JMS\Groups({"list"})
     */
    private $uv;

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @Expose
     * @JMS\Groups({"list"})
     */
    private $dv;

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @Expose
     * @JMS\Groups({"list"})
     */
    private $voteCount;


    /**
     * @ORM\Column(type="string")
     */
    private $publicIP;

    /**
     * @ORM\Column(type="string")
     */
    private $privateIP;

    /**
     * @ORM\OneToMany(targetEntity="Red\EntriesBundle\Entity\EntryVoters", mappedBy="entry")
     * @Expose
     * @JMS\Groups({"list"})
     */
    private $voters;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->voters = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set text
     *
     * @param string $text
     *
     * @return Entry
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set uv
     *
     * @param integer $uv
     *
     * @return Entry
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
     * @return Entry
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
     * @return Entry
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
     * @return Entry
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
     * @return Entry
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
     * Set user
     *
     * @param \Red\UserBundle\Entity\User $user
     *
     * @return Entry
     */
    public function setUser(\Red\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Red\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add voter
     *
     * @param \Red\EntriesBundle\EntryVoters $voter
     *
     * @return Entry
     */
    public function addVoter(\Red\EntriesBundle\EntryVoters $voter)
    {
        $this->voters[] = $voter;

        return $this;
    }

    /**
     * Remove voter
     *
     * @param \Red\EntriesBundle\EntryVoters $voter
     */
    public function removeVoter(\Red\EntriesBundle\EntryVoters $voter)
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
