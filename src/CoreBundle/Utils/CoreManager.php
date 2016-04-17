<?php
/**
 * Created by PhpStorm.
 * User: Lukasz Malicki
 * Date: 17.04.16
 * Time: 12:16
 */

namespace CoreBundle\Utils;

use ChannelBundle\Entity\Channel;
use Doctrine\ORM\EntityManager;
use UserBundle\Entity\User;

class CoreManager
{
    private $_em;

    public function __construct(EntityManager $em)
    {
        $this->_em = $em;
    }

    // Changes "@username" and "g/groupname" into markdown links
    public function parseText($body)
    {
        $user = $this->_em->getRepository(User::class);
        $channel = $this->_em->getRepository(Channel::class);

//        $body = preg_replace_callback('/(?<=^|\s)c\/([a-z0-9_-]+)(?=$|\s|:|.)/i', function ($matches) {
//            $content = Content::find($matches[1]);
//            if ($content) {
//                return '[' . str_replace('_', '\_', $content->title) . '](' . $content->getSlug() . ')';
//            } else {
//                return 'c/' . $matches[1];
//            }
//        }, $body);

        /**
         * search for "@username" in text
         */
        $body = preg_replace_callback('/(?<=^|\s)u\/([a-z0-9_-]+)(?=$|\s|:|.)/i', function ($matches) use ($user) {
            $target = $user->findOneBy(['username' => $matches[1]]);

            if ($target)
                return '[u/' . str_replace('_', '\_', $target->getUsername()) . '](/u/' . $target->getUsername() . ')';
            return 'u/' . $matches[1];

        }, $body);

        $body = preg_replace_callback('/(?<=^|\s)@([a-z0-9_-]+)(?=$|\s|:|.)/i', function ($matches) use ($user) {
            $target = $user->findOneBy(['username' => $matches[1]]);
            if ($target)
                return '[@' . str_replace('_', '\_', $target->getUsername()) . '](/u/' . $target->getUsername() . ')';
            return '@' . $matches[1];
        }, $body);

        /**
         * search for group in text
         */
        $body = preg_replace_callback('/(?<=^|\s)(?<=\s|^)g\/([a-z0-9_-żźćńółęąśŻŹĆĄŚĘŁÓŃ]+)(?=$|\s|:|.)/i', function ($matches) use ($channel) {
            $target = $channel->findByNameInsensitive($matches[1]);

//            $fakeGroup = class_exists('Folders\\' . studly_case($matches[1]));
//            if ($target || $fakeGroup) {
            if ($target) {
                $urlName = $target ? $target->getName() : $matches[1];
                return '[g/' . str_replace('_', '\_', $urlName) . '](/g/' . $urlName . ')';
            }
            return 'g/' . $matches[1];

        }, $body);

        return $body;
    }
}