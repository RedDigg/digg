<?php

namespace Red\EntriesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\MaxDepth;
use FOS\CommentBundle\Entity\Thread as BaseThread;
use FOS\CommentBundle\Model\SignedCommentInterface;
use Red\CoreBundle\Traits\Timestampable;

/**
 * @ORM\Entity
 * @ORM\Table()
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 */
class Entry extends BaseThread
{

    /**
     * Hook SoftDeleteable behavior
     * updates deletedAt field
     */
//    use SoftDeleteableEntity;
    use BlameableEntity;
//    use Timestampable;

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;



}
