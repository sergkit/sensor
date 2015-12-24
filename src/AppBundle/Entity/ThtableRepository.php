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

}
