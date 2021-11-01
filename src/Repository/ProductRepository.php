<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Request that allows me to retrieve products based on user research
     * @return Product[]
     */
    public function findWithSearch(Search $search) {                // Create request
        $query = $this
            ->createQueryBuilder('p')                               // Mapping with Product table
            ->select('c', 'p')                                      // Select Category and Products in this query
            ->join('p.category', 'c');                              // Join between product categories and the category table

        if (!empty($search->categories)) {                          // Allow access to the 'string' and 'categories' properties of Search.php because they are not private
            $query = $query
                ->andWhere('c.id IN (:categories)')                 // I need the category Id to be in the category list
                ->setParameter('categories', $search->categories);  // The value of 'categories' above will be what is in the 'search categories' object
        }

        if (!empty($search->string)) {                              // text search
            $query = $query
                ->andWhere('p.name LIKE :string')                   // Does the product name look like the filter search
                ->setParameter('string', "%{$search->string}%");    // Allows you to do a partial search of the name
        }

        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
