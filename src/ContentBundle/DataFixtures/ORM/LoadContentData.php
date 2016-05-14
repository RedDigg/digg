<?php
/**
 * Created by PhpStorm.
 * User: lmalicki
 * Date: 11.11.15
 * Time: 12:48
 */

namespace ContentBundle\DataFixtures\ORM;

use ChannelBundle\Entity\Channel;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Proxies\__CG__\ContentBundle\Entity\Content;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadContentData implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $userManager = $manager->getRepository('UserBundle:User');
        $channelManager = $manager->getRepository('ChannelBundle:Channel');

        foreach ($this->contentList() as $contentArr) {
            $content = new Content();

            $user = $userManager->findOneByUsername($contentArr['username']);

            if (!$user) {
                throw new NotFoundHttpException(
                    sprintf(
                        'Unable to load ContentData fixtures. Unable to find \'%s\' user.',
                        $contentArr['username']
                    )
                );
            }
            $content
                ->setCreatedBy($user)
                ->setUpdatedBy($user)
                ->setTitle($contentArr['title'])
                ->setDescription($contentArr['description'])
                ->setEng($contentArr['eng'])
                ->setNsfw($contentArr['nsfw']);

            foreach ($contentArr['channels'] as $channel) {
                $newChannel = $channelManager->findOneByName($channel);
                if(!$newChannel) {
                    throw new NotFoundHttpException(
                        sprintf('Unable to load ContentData fixtures. Unable to find \'%s\' channel.', $channel)
                    );
                }

                $content->addChannel($newChannel);
            }

            $manager->persist($content);
        }

        $manager->flush();
    }

    private function contentList()
    {

        $list = [
            [
                'channels' => ['nsfw', 'wild', 'geeks'],
                'username' => 'user1',
                'title' => 'First content',
                'description' => 'This is description of the first content!',
                'eng' => false,
                'nsfw' => false
            ],
            [
                'channels' => ['cars', 'jointclub', 'geeks'],
                'username' => 'user1',
                'title' => 'Second content',
                'description' => 'Lorem ipsum dolor sit amet el de la cruz ina un bugtha',
                'eng' => true,
                'nsfw' => false
            ],
            [
                'channels' => ['billgates', 'bikes', 'geeks'],
                'username' => 'user2',
                'title' => 'Third content',
                'description' => 'Bla bla la la de la lu kiap rek polozn, sia suai rol.',
                'eng' => true,
                'nsfw' => false
            ],
            [
                'channels' => ['wild', 'geeks'],
                'username' => 'user3',
                'title' => 'Fourth content with very long title description.',
                'description' => '',
                'eng' => false,
                'nsfw' => false
            ],
            [
                'channels' => ['bikes', 'cars', 'geeks'],
                'username' => 'user1',
                'title' => 'Fifth content',
                'description' => 'Riki tiki narkotiki',
                'eng' => true,
                'nsfw' => true
            ],
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
        return 3;
    }
}