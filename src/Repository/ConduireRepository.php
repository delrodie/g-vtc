<?php

namespace App\Repository;

use App\Entity\Conduire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conduire>
 */
class ConduireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conduire::class);
    }

    public function findVehiculeByChauffeur($chauffeur)
    {
        return $this->queryJoin()
            ->where('f.id = :id')
            ->andWhere('c.statut = :statut')
            ->setParameters( new ArrayCollection([
                new Parameter('id', $chauffeur),
                new Parameter('statut', true)
            ]))
            ->getQuery()->getOneOrNullResult();
    }

    public function findAllVehiculeConduitsByChauffeur($chauffeur)
    {
        return $this->queryJoin()
            ->where('f.id = :id')
            ->andWhere('c.statut = :statut')
            ->setParameters(new ArrayCollection([
                new Parameter('id', $chauffeur),
                new Parameter('statut', false)
            ]))
            ->getQuery()->getResult();
    }

    public function queryJoin()
    {
        return $this->createQueryBuilder('c')
            ->addSelect('v')
            ->addSelect('f')
            ->addSelect('m')
            ->leftJoin('c.vehicule', 'v')
            ->leftJoin('c.chauffeur', 'f')
            ->leftJoin('v.marque', 'm')
            ;
    }

    //    /**
    //     * @return Conduire[] Returns an array of Conduire objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Conduire
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
