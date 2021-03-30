<?php

namespace App\Repository;

use App\Entity\Cocktail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cocktail|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cocktail|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cocktail[]    findAll()
 * @method Cocktail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CocktailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cocktail::class);
    }

    public function search($category, $createdAtMin)
    {
        $queryBuilder = $this->createQueryBuilder('c');

        if ($category) {
            $queryBuilder->andWhere('c.category = :category')
                         ->setParameter('category', $category);
        }

        if ($createdAtMin) {
            $queryBuilder->andWhere('c.createdAt >= :created_at_min')
                         ->setParameter('created_at_min', $createdAtMin);
        }

        return $queryBuilder
                    ->orderBy('c.createdAt', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    // /**
    //  * @return Cocktail[] Returns an array of Cocktail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cocktail
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
