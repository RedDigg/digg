<?php

namespace Red\EntriesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table()
 *
 */
class EntryVoters
{
    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Red\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Red\EntriesBundle\Entity\Entry", inversedBy="voters")
     * @ORM\JoinColumn(name="entry", referencedColumnName="id", nullable=true)
     */
    private $entry;


    /**
     * @ORM\ManyToOne(targetEntity="Red\EntriesBundle\Entity\Entry", inversedBy="voters")
     * @ORM\JoinColumn(name="entryComment", referencedColumnName="id", nullable=true)
     */
    private $entryComment;

    /**
     * Set user
     *
     * @param \Red\UserBundle\Entity\User $user
     *
     * @return EntryVoters
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
     * @param \Red\EntriesBundle\Entry $entry
     *
     * @return EntryVoters
     */
    public function setEntry(\Red\EntriesBundle\Entry $entry)
    {
        $this->entry = $entry;

        return $this;
    }

    /**
     * Get entry
     *
     * @return \Red\EntriesBundle\Entry
     */
    public function getEntry()
    {
        return $this->entry;
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
     * Set entryComment
     *
     * @param \Red\EntriesBundle\Entity\Entry $entryComment
     *
     * @return EntryVoters
     */
    public function setEntryComment(\Red\EntriesBundle\Entity\Entry $entryComment)
    {
        $this->entryComment = $entryComment;

        return $this;
    }

    /**
     * Get entryComment
     *
     * @return \Red\EntriesBundle\Entity\Entry
     */
    public function getEntryComment()
    {
        return $this->entryComment;
    }
}
