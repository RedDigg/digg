<?php

namespace UserBundle\Entity;

use CoreBundle\Traits\Timestampable;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
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
    use Timestampable;

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
     * TRUE if account is locked
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     */
    protected $locked;

    /**
     * [min: 5, max:40]
     * @Assert\Length(
     *      min = 5,
     *      max = 40,
     *      minMessage = "Field 'username' should be at least {{ limit }} characters long",
     *      maxMessage = "Field 'username' cannot be longer than {{ limit }} characters"
     * )
     *
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     */
    protected $username;

    /**
     * Contains user roles
     *
     * @Expose
     * @JMS\Groups({"user","mod","admin"})
     */
    protected $roles;

    /**
     * Contains user email. Visible only to administrator.
     *
     * @Expose
     * @JMS\Groups({"admin"})
     */
    protected $email;
}
