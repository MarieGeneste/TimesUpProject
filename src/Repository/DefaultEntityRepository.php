<?php

namespace App\Repository;

use App\Entity\DefaultEntity;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method DefaultEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method DefaultEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method DefaultEntity[]    findAll()
 * @method DefaultEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DefaultEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DefaultEntity::class);
    }




}
