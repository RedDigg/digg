<?php
/**
 * Created by PhpStorm.
 * User: lmalicki
 * Date: 11.11.15
 * Time: 12:48
 */

namespace ChannelBundle\DataFixtures\ORM;

use ChannelBundle\Entity\Channel;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadChannelData implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = $manager->getRepository('UserBundle:User')->findOneBy(['username'=>'system']);

        if (!$user) {
            throw new NotFoundHttpException('Unable to find \'system\' user. Please check LoadUserData fixtures.');
        }

        foreach ($this->channelList() as $channelArr) {
            $channel = new Channel();

            $channel
                ->setName($channelArr['name'])
                ->setDescription($channelArr['description'])
                ->setPublic($channelArr['public'])
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
                ->setCreatedBy($user)
                ->setUpdatedBy($user);

            $manager->persist($channel);
        }

        $manager->flush();
    }

    private function channelList()
    {
        $list = [
            ['name' => 'nsfw', 'description' => '', 'public' => true],
            ['name' => 'wild', 'description' => 'Lorem ipsum dolor sit amet bla bla bla', 'public' => true],
            ['name' => 'geeks', 'description' => '', 'public' => false],
            ['name' => 'ganja', 'description' => 'Like to smoke?', 'public' => false],
            ['name' => 'jointclub', 'description' => 'Like to smoke?', 'public' => true],
            [
                'name' => 'cars',
                'description' => 'We really love cars! Here you can find anything about them.',
                'public' => true
            ],
            ['name' => 'bikes', 'description' => '', 'public' => true],
            ['name' => 'billgates', 'description' => 'This is my private group.', 'public' => false],
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