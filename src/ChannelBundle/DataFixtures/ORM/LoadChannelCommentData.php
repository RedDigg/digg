<?php
/**
 * Created by PhpStorm.
 * User: lmalicki
 * Date: 11.11.15
 * Time: 12:48
 */

namespace ChannelBundle\DataFixtures\ORM;

use ContentBundle\Entity\ContentComment;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadChannelCommentData implements FixtureInterface, OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $userManager = $manager->getRepository('UserBundle:User');
        $contentManager = $manager->getRepository('ContentBundle:Content');

        $comments = [];
        foreach ($this->channelList() as $index => $commentArr) {
            $comment = new ContentComment();

            $user = $userManager->findOneByUsername($commentArr['username']);

            if (!$user) {
                throw new NotFoundHttpException(sprintf('Unable to find \'%s\' user.', $commentArr['username']));
            }

            $content = $contentManager->findOneByTitle($commentArr['contentTitle']);

            if (!$content) {
                throw new NotFoundHttpException(sprintf('Unable to find \'%s\' content.', $commentArr['contentTitle']));
            }

            $parent = $commentArr['parent_id'] ? $comments[$commentArr['parent_id']] : null;
            $comment
                ->setCreatedBy($user)
                ->setUpdatedBy($user)
                ->setBody($commentArr['body'])
                ->setContent($content)
                ->setParent($parent);

            if(isset($commentArr['deleted']) and true === $commentArr['deleted']) {
                $comment->setDeletedAt((new \DateTime()));
            }

            $manager->persist($comment);

            $comments[$index] = $comment;
        }

        $manager->flush();
    }

    private function channelList()
    {
        $list = [
            0 => [
                'body' => 'This is my first content and my first comment. Please by gentle...',
                'username' => 'user1',
                'contentTitle' => 'First content',
                'parent_id' => null,
            ],
            1 => [
                'body' => 'OK, no problem @user1! I\'ll be gentle but I don\'t know if @user3 will be...',
                'username' => 'user2',
                'contentTitle' => 'First content',
                'parent_id' => 0,
            ],
            3 => [
                'body' => 'This is my first comment also...',
                'username' => 'user3',
                'contentTitle' => 'First content',
                'parent_id' => null,
                'deleted' => true,
            ],
            4 => [
                'body' => 'Bla bla bla',
                'username' => 'user3',
                'contentTitle' => 'Second content',
                'parent_id' => null,
            ],
            5 => [
                'body' => 'Bla bla bla',
                'username' => 'user2',
                'contentTitle' => 'Second content',
                'parent_id' => null,
            ],
            6 => [
                'body' => 'Bla bla bla',
                'username' => 'user1',
                'contentTitle' => 'Second content',
                'parent_id' => null,
            ],
            7 => [
                'body' => 'Bla bla bla 1',
                'username' => 'user1',
                'contentTitle' => 'Second content',
                'parent_id' => 6,
            ],
            8 => [
                'body' => 'Bla bla bla 2',
                'username' => 'user1',
                'contentTitle' => 'Second content',
                'parent_id' => 6,
            ],
            9 => [
                'body' => 'Bla bla bla 3',
                'username' => 'user2',
                'contentTitle' => 'Second content',
                'parent_id' => 6,
            ],
            10 => [
                'body' => 'Bla bla bla 3.1',
                'username' => 'user1',
                'contentTitle' => 'Second content',
                'parent_id' => 9,
            ],
            11 => [
                'body' => 'Bla bla bla 3.2',
                'username' => 'user2',
                'contentTitle' => 'Second content',
                'parent_id' => 9,
            ],
            12 => [
                'body' => 'Bla bla bla 3.3',
                'username' => 'user3',
                'contentTitle' => 'Second content',
                'parent_id' => 9,
            ],
            13 => [
                'body' => 'Bla bla bla 3.3.1',
                'username' => 'user1',
                'contentTitle' => 'Second content',
                'parent_id' => 12,
            ],
            14 => [
                'body' => 'Bla bla bla 3.3.2',
                'username' => 'user2',
                'contentTitle' => 'Second content',
                'parent_id' => 12,
            ],
            15 => [
                'body' => 'Bla bla bla 3.3.2',
                'username' => 'user3',
                'contentTitle' => 'Second content',
                'parent_id' => 12,
            ],
            16 => [
                'body' => 'Bla bla bla 3.3.3',
                'username' => 'user2',
                'contentTitle' => 'Second content',
                'parent_id' => 15,
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
        return 4;
    }
}