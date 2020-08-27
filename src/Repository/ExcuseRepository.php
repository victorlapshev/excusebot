<?php

namespace App\Repository;

use App\Entity\Excuse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Excuse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Excuse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Excuse[]    findAll()
 * @method Excuse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExcuseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Excuse::class);
    }

    // /**
    //  * @return Excuse[] Returns an array of Excuse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Excuse
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return Excuse
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function findRandom(): Excuse
    {
        return $this->createQueryBuilder('e')
            ->orderBy('Rand()')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }
}
