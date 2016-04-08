<?php

namespace UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="oauth2_user")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ExclusionPolicy("all")
 */
class User extends BaseUser
{
    /**
     * Hook SoftDeleteable behavior
     * updates deletedAt field
     */
    use SoftDeleteableEntity;
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @JMS\Groups({"mod","admin"})
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     */
    protected $username;

    /**
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     */
    protected $locked;

    /**
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     */
    protected $createdAt;
}
