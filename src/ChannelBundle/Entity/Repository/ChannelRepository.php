<?php
/**
 * Created by PhpStorm.
 * User: Lukasz Malicki
 * Date: 17.04.16
 * Time: 13:55
 */

namespace ChannelBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class ChannelRepository extends EntityRepository
{
    public function findByNameInsensitive($name)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('c')
            ->from('ChannelBundle:Channel', 'c')
            ->where('lower(c.name) = :name ')
            ->setParameter(':name', strtolower($name))
            ->getQuery()->getSingleResult();
    }
}