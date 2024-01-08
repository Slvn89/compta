<?php

namespace App\DataFixtures;

use App\Entity\Fournisseur;
use App\Entity\Secteur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FournisseurFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Ajout des fournisseurs pour les stations essence
        $fournisseur1 = new Fournisseur();
        $fournisseur1->setSecteur($manager->getReference(Secteur::class, 1)); // Utilisez l'ID du secteur "station service"
        $fournisseur1->setNom("Esso");
        $manager->persist($fournisseur1);

        $fournisseur2 = new Fournisseur();
        $fournisseur2->setSecteur($manager->getReference(Secteur::class, 1)); // Utilisez l'ID du secteur "station service"
        $fournisseur2->setNom("Total");
        $manager->persist($fournisseur2);

        // Ajout des fournisseurs pour les pharmacies
        $fournisseur3 = new Fournisseur();
        $fournisseur3->setSecteur($manager->getReference(Secteur::class, 2)); // Utilisez l'ID du secteur "pharmacie"
        $fournisseur3->setNom("Belvedere");
        $manager->persist($fournisseur3);

        $fournisseur4 = new Fournisseur();
        $fournisseur4->setSecteur($manager->getReference(Secteur::class, 2)); // Utilisez l'ID du secteur "pharmacie"
        $fournisseur4->setNom("Centrale");
        $manager->persist($fournisseur4);

        $manager->flush();
    }
}
