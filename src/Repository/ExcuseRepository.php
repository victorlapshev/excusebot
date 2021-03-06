<?php

namespace App\Repository;

use App\Entity\Excuse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Elastica\Query;
use Elastica\Result;
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
        foreach ($this->searchIndex($text) as $result) {
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

    /**
     * @param string $text
     * @return Result[]
     */
    public function searchIndex(string $text)
    {
        $boolQuery = new Query\BoolQuery();
        $boolQuery->addMust(new Query\Match('text', $text));

        $finalQuery = new \Elastica\Query($boolQuery);
        $finalQuery->setHighlight(
            [
                'pre_tags' => ['*'],
                'post_tags' => ['*'],
                'fields' => [
                    'text' => [
                        'fragment_size' => 80,
                        'number_of_fragments' => 1
                    ]
                ]
            ]
        );

        $this->elasticSearch
            ->addIndex('excuse')
            ->setQuery($finalQuery);

        $search = $this->elasticSearch->search();

        return $search->getResults();
    }
}
