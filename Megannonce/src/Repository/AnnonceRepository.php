<?php

namespace App\Repository;

use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    /**
     * Recherche les annonces en fonctions du formulaire
     */
    public function search($mots = null, $price = null, $category = null, $location = null){
        $query = $this->createQueryBuilder('a');
         
        if($mots != null){
            $query->andWhere('MATCH_AGAINST(a.title, a.description) AGAINST (:mots boolean)>0')->setParameter('mots', $mots);
        }

        if($price != null){
            $query->andWhere('a.price <= :price')->setParameter('price', $price);
        }

        if($category){
            $query->andWhere('a.category = :category')->setParameter('category', $category);
        }
        if($location){
            $query->andWhere('a.location <= :location')->setParameter('location', $location);
        }
        return $query->getQuery()->getResult();
    }

    // /**
    //  * @return Annonce[] Returns an array of Annonce objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Annonce
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
