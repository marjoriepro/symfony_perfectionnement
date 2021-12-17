<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    public function searchByTerm($term)
    {
        //queryBuilder permet de créer des requetes SQL en PHP 
        $queryBuilder = $this->createQueryBuilder('product');

        // Requete : 
        $query = $queryBuilder
            ->select('product')
            ->leftJoin('product.type', 'type') //leftJoin sur la table type
            ->leftJoin('product.brand', 'brand') //leftJoin sur la table brand
            ->where('product.name LIKE :term')
            ->orWhere('product.description LIKE :term')
            ->orWhere('type.name LIKE :term')
            ->orWhere('type.description LIKE :term')
            ->orWhere('brand.name LIKE :term')
            ->orWhere('brand.description LIKE :term')
            ->setParameter('term', '%' . $term . '%')
            // on attribue le term rentré et on sécurise
            ->getQuery();
            
            return $query->getResult();
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
