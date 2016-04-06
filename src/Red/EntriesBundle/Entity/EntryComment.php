<?php

namespace Red\EntriesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity
 * @ORM\Table()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 *
 */
class EntryComment
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
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Red\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Red\EntriesBundle\Entity\Entry")
     * @ORM\JoinColumn(name="entry", referencedColumnName="id")
     */
    private $entry;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $text;

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private $uv;

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private $dv;

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
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
     */
    private $voters;


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
     * @return EntryComment
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
     * Set user
     *
     * @param \Red\UserBundle\Entity\User $user
     *
     * @return EntryComment
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
     * Set entry
     *
     * @param \Red\EntriesBundle\Entity\Entry $entry
     *
     * @return EntryComment
     */
    public function setEntry(\Red\EntriesBundle\Entity\Entry $entry = null)
    {
        $this->entry = $entry;

        return $this;
    }

    /**
     * Get entry
     *
     * @return \Red\EntriesBundle\Entity\Entry
     */
    public function getEntry()
    {
        return $this->entry;
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
     * @param \Red\EntriesBundle\Entity\EntryVoters $voter
     *
     * @return EntryComment
     */
    public function addVoter(\Red\EntriesBundle\Entity\EntryVoters $voter)
    {
        $this->voters[] = $voter;

        return $this;
    }

    /**
     * Remove voter
     *
     * @param \Red\EntriesBundle\Entity\EntryVoters $voter
     */
    public function removeVoter(\Red\EntriesBundle\Entity\EntryVoters $voter)
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
