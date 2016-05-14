<?php
/**
 * Created by PhpStorm.
 * User: lmalicki
 * Date: 11.11.15
 * Time: 12:48
 */

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadUserData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $_container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->_container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $userManager = $this->_container->get('fos_user.user_manager');
        $factory = $this->_container->get('security.encoder_factory');

        foreach ($this->userList() as $userArr) {

            $user = $userManager->createUser();

            $user
                ->setUsername($userArr['username'])
                ->setEmail(sprintf('%s@localhost.net', $userArr['username']))
                ->setRoles($userArr['roles'])
                ->setEnabled($userArr['enabled']);

            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($userArr['password'], $user->getSalt());
            $user->setPassword($password);

            $userManager->updateUser($user);

        }

    }

    public function userList()
    {
        $list = [
            [
                'username' => 'system',
                'roles' => ['ROLE_ADMIN','ROLE_API'],
                'password' => sha1((new \DateTime())->getTimestamp() . mt_rand(0, 100)),
                'enabled' => false
            ],
            ['username' => 'root', 'roles' => ['ROLE_ADMIN','ROLE_API'], 'password' => 'root', 'enabled' => true],
            ['username' => 'moderator', 'roles' => ['ROLE_MOD','ROLE_API'], 'password' => 'moderator', 'enabled' => true],
            ['username' => 'user1', 'roles' => ['ROLE_USER','ROLE_API'], 'password' => 'user1', 'enabled' => true],
            ['username' => 'user2', 'roles' => ['ROLE_USER','ROLE_API'], 'password' => 'user2', 'enabled' => true],
            ['username' => 'user3', 'roles' => ['ROLE_USER','ROLE_API'], 'password' => 'user3', 'enabled' => true],
        ];

        return $list;
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 1;
    }
}