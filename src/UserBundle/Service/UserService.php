<?php
/**
 * Created by PhpStorm.
 * User: lmalicki
 * Date: 03.05.16
 * Time: 21:04
 */

namespace UserBundle\Service;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserService
{
    /**
     * @var EntityManager
     */
    private $_user;

    private $_authorizationChecker;

    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationCheckerInterface $authorizationChecker)
    {
        if($tokenStorage->getToken() === null) {
            throw new AccessDeniedException();
        }

        $this->_user = $tokenStorage->getToken()->getUser();
        $this->_authorizationChecker = $authorizationChecker;
    }

    public function getGrantedAPIGroups()
    {
        $roles = [];
        if($this->_authorizationChecker->isGranted('ROLE_ADMIN')) {
            $roles[] = 'admin';
        } elseif($this->_authorizationChecker->isGranted('ROLE_MODERATOR')) {
            $roles[] = 'mod';
        } else {
            $roles[] = 'user';
        }

        return $roles;
    }
}