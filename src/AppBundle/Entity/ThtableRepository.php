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
    public function findAllForRoomLastDay($room=1, $interval="1D") {
        $startDate=new \DateTime('now');
        $qb = $this->createQueryBuilder('th');
        $qb->where('th.room = :room')
                ->andWhere('th.date>:dd')
                ->addOrderBy('th.date')
                ->setParameter('room', $room)
                ->setParameter('dd', $startDate->sub(new \DateInterval("P$interval")));
        return $qb->getQuery();
    }
/**
 * Выдает массив с усредненными показаниями датчиков за интервал PT7M для заданной комнаты
 * @param \AppBundle\Entity\Rooms $room
 * @return Array
 */
    public function getLastStat(\AppBundle\Entity\Rooms $room){
        $startDate=new \DateTime('now');
        $qb = $this->createQueryBuilder('th');
        $qb->select('avg(th.co2) as avg_co2', 'avg(th.t) as avg_t' , 'avg(th.h)  as avg_h', 'avg(th.voc)  as avg_voc')
                ->where('th.room = :room')
                ->andWhere('th.date>:dd')
                ->groupBy('th.room')
                ->setParameter('room', $room->getId())
                ->setParameter('dd', $startDate->sub(new \DateInterval("PT7M")));
        return $qb->getQuery()->getArrayResult();
    }

}
