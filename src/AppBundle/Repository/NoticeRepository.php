<?php

namespace AppBundle\Repository;

/**
 * NoticeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NoticeRepository extends \Doctrine\ORM\EntityRepository
{
    public function getActualNotices()
    {
        $em = $this->getEntityManager();
        $notices = $em->createQuery('SELECT n FROM AppBundle:Notice n WHERE n.expiration > CURRENT_TIMESTAMP()')->getResult();

        return $notices;
    }
}
