<?php

namespace App\Repository;

use App\Entity\Portefeuille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Portefeuille>
 */
class PortefeuilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Portefeuille::class);
    }

    public function findOperationByTypeAndPeriode($type, $dateDebut, $dateFin)
    {
        return $this->queyJoin()
            ->where('p.type = :type')
            ->andWhere('p.date BETWEEN :dateDebut AND :dateFin')
            ->orderBy('p.date', 'DESC')
            ->setParameters(new ArrayCollection([
                new Parameter('type', $type),
                new Parameter('dateDebut', $dateDebut),
                new Parameter('dateFin', $dateFin)
            ]))
            ->getQuery()->getResult();
    }

    public function findOperationByPeriode(array $periode)
    {
        return $this->queyJoin()
            ->where('p.date BETWEEN :dateDebut AND :dateFin')
            ->orderBy('p.date', 'DESC')
            ->setParameters(new ArrayCollection([
                new Parameter('dateDebut', $periode['dateDebut']),
                new Parameter('dateFin', $periode['dateFin'])
            ]))
            ->getQuery()->getResult();
    }

    public function findMontantByTypeAndPeriode($type, $dateDebut, $dateFin)
    {
        return $this->queyJoin()
            ->select('SUM(p.montant)')
            ->where('p.type = :type')
            ->andWhere('p.date BETWEEN :dateDebut AND :dateFin')
            ->setParameters(new ArrayCollection([
                new Parameter('type', $type),
                new Parameter('dateDebut', $dateDebut),
                new Parameter('dateFin', $dateFin)
            ]))
            ->getQuery()->getSingleScalarResult();
    }

    public function queyJoin()
    {
        return $this->createQueryBuilder('p')
            ->addSelect('v')
            ->leftJoin('p.vehicule', 'v')
            ;
    }

    //    /**
    //     * @return Portefeuille[] Returns an array of Portefeuille objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Portefeuille
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
