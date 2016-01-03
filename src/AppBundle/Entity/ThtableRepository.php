<?php

namespace AppBundle\Entity;

/*
 * Сервер домашней автоматизации.
 * author kitserg68@gmail.com
 *
 */

use Doctrine\ORM\EntityRepository;

/**
 * Description of ThtableRepository
 *
 * @author benjuchis
 */
class ThtableRepository extends EntityRepository {

    public function findAllForRoom() {
        $qb = $this->createQueryBuilder('th');
        $qb->where('th.room = 1');

        return $qb->getQuery();
    }
/**
 * запрос на получений данных по комнате в заданный интервал вермени T30H  30D
 * @param int $room
 * @param string $interval
 * @return Query
 */
    public function findAllForRoomLastDay($room=1, $interval='T200H') {
        $startDate=new \DateTime('now');
        $qb = $this->createQueryBuilder('th');
        $qb->where('th.room = :room')
                ->andWhere('th.date>:dd')
                ->setParameter('room', $room)
                ->setParameter('dd', $startDate->sub(new \DateInterval("P$interval")));
        return $qb->getQuery();
    }

}
