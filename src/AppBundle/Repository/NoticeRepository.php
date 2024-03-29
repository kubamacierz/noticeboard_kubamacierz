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
        return $this->getEntityManager()->createQuery(
            'SELECT n FROM AppBundle:Notice n WHERE n.expiration > CURRENT_TIMESTAMP()'
        )->getResult();
    }

    public function getActualNoticesById($id)
    {
        return  $this->getEntityManager()->createQuery(
            'SELECT n FROM AppBundle:Notice n WHERE n.expiration > CURRENT_TIMESTAMP() AND n.user = :id'
        )->setParameters(['id' => $id])->getResult();
    }
}
