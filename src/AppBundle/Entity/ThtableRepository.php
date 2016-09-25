<?php

namespace AppBundle\Entity;

/*
 * Сервер домашней автоматизации.
 * author kitserg68@gmail.com
 *
 */

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

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
        $qb = $this->createQueryBuilder('th');
        $qb->where('th.room = :room')
                ->andWhere('th.date>:dd')
                ->addOrderBy('th.date')
                ->setParameter('room', $room)
                ->setParameter('dd', $this->getDateInterval("P$interval"));
        return $qb->getQuery();
    }
/**
 * Выдает массив с усредненными показаниями датчиков за интервал PT7M для заданной комнаты
 * @param \AppBundle\Entity\Rooms $room
 * @return Array
 */
    public function getLastStat(\AppBundle\Entity\Rooms $room){
        $qb = $this->createQueryBuilder('th');
        $qb->select('avg(th.co2) as avg_co2', 'avg(th.t) as avg_t' , 'avg(th.h)  as avg_h', 'avg(th.voc)  as avg_voc', 'max(th.deh) as deh')
                ->where('th.room = :room')
                ->andWhere('th.date>:dd')
                ->groupBy('th.room')
                ->setParameter('room', $room->getId())
                ->setParameter('dd', $this->getDateInterval());
        return $qb->getQuery()->getArrayResult();
    }
/**
 * Выдает массив с усредненными показаниями датчиков за интервал PT7M для всех комнат
 * @return Array
 */
    public function getAllStat(){

        $qb = $this->createQueryBuilder('th');
        $qb->select('r.name', 'avg(th.co2) as avg_co2', 'avg(th.t) as avg_t' , 'avg(th.h)  as avg_h', 'avg(th.voc)  as avg_voc')
                ->innerJoin('AppBundle\Entity\Rooms', 'r', Join::WITH, 'r.id=th.room')
                ->Where('th.date>:dd')
                ->groupBy('r.name')
                ->setParameter('dd', $this->getDateInterval());
        return $qb->getQuery()->getArrayResult();
    }

    private function getDateInterval($s="PT7M"){
      $startDate=new \DateTime("now", new \DateTimeZone("Europe/Moscow"));
      return $startDate->sub(new \DateInterval($s));
    }
}
