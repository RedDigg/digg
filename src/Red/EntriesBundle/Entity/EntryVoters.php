<?php

namespace Red\EntriesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use FOS\CommentBundle\Entity\Vote as BaseVote;

/**
 * @ORM\Entity
 * @ORM\Table()
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class EntryVoters extends BaseVote
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Comment of this vote
     *
     * @var Comment
     * @ORM\ManyToOne(targetEntity="Red\EntriesBundle\Entity\EntryComment")
     */
    protected $comment;
}
