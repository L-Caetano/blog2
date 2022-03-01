<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    //funcao para pegar as postagens de dentro da categoria
    public function getPostagens($id){
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT p
            FROM App\Entity\Postagem p
            JOIN p.categories c
            WHERE c.id = :id'
        )->setParameter('id', $id);
        return $query->getResult();
    }
    //funcao que retorna uma postagem só 
    public function getPostagem($id){

        // $query = $em->createQueryBuilder(
        //     'SELECT p
        //     FROM App\Entity\Postagem p
        //     JOIN p.categories c
        //     WHERE c.id = :id
        //     ')
    }
    // /**
    //  * @return Category[] Returns an array of Category objects
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
    public function findOneBySomeField($value): ?Category
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
