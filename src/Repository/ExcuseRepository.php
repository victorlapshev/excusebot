<?php

namespace App\Repository;

use App\Entity\Excuse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Elastica\Query;
use Elastica\Query\Term;
use Elastica\Search;

/**
 * @method Excuse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Excuse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Excuse[]    findAll()
 * @method Excuse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExcuseRepository extends ServiceEntityRepository
{
    /**
     * @var Search
     */
    private $elasticSearch;

    public function __construct(ManagerRegistry $registry, Search $elasticSearch)
    {
        parent::__construct($registry, Excuse::class);
        $this->elasticSearch = $elasticSearch;
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
    public function findRandomOne(): Excuse
    {
        return $this->createQueryBuilder('e')
            ->orderBy('Rand()')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }

    public function findRandom(int $count = 3): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('Rand()')
            ->setMaxResults($count)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $text
     * @return Excuse[]
     */
    public function findByText(string $text)
    {
        $boolQuery = new Query\BoolQuery();
        $boolQuery->addMust(new Query\Match('text', $text));

        $this->elasticSearch
            ->addIndex('excuse')
            ->setQuery($boolQuery);

        $search = $this->elasticSearch->search();

        foreach ($search->getResults() as $result) {
            $ids[] = $result->getId();
        }

        if (empty($ids)) {
            return [];
        }

        return $this->createQueryBuilder('e')
            ->where(sprintf('e.id in (%s)', implode(',', $ids)))
            ->getQuery()
            ->getResult();
    }
}
