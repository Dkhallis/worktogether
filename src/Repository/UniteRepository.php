<?php

namespace App\Repository;

use App\Entity\Unite;
use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Unite>
 */
class UniteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Unite::class);
    }

//    /**
//     * @return Unite[] Returns an array of Unite objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Unite
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
     * Renvoie les unités d'un client : celles qui font partie
     * d'une de ses commandes, triées par baie puis par numéro.
     */
    public function findByClient(Client $client): array
    {
        return $this->createQueryBuilder('u')
            ->join('u.commandes', 'c')
            ->andWhere('c.client = :client')
            ->setParameter('client', $client)
            ->leftJoin('u.baie', 'b')
            ->addSelect('b')
            ->distinct()
            ->orderBy('b.numero')
            ->addOrderBy('u.numero')
            ->getQuery()
            ->getResult();
    }
}
