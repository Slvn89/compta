<?php

// src/Repository/FournisseurRepository.php

namespace App\Repository;

use App\Entity\Fournisseur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fournisseur>
 */
class FournisseurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fournisseur::class);
    }

    /**
     * @return Fournisseur[] Returns an array of Fournisseur objects by Secteur
     */
    public function findBySecteur(int $secteurId): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.secteur = :secteurId')
            ->setParameter('secteurId', $secteurId)
            ->getQuery()
            ->getResult();
    }

    // Ajoutez d'autres méthodes selon vos besoins
}
