<?php

namespace ContentBundle\Entity\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * ClickRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContentRepository extends \Doctrine\ORM\EntityRepository
{
    public function getNewestContents($page, $limit, $channels)
    {
        $query = $this->getEntityManager()->createQueryBuilder('c')
            ->where('c.deletedAt is null')
            ->andWhere('c.channels in (:channels)')
            ->orderBy('c.createdAt', 'DESC')
            ->setParameter('channels', $channels)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        $content = new Paginator($query, $fetchJoinCollection = true);

        return [
            'content' => $content->getQuery()->getResult(),
            'total' => count($content),
        ];
    }

}