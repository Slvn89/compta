<?php

namespace App\DataFixtures;

use App\Entity\Secteur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SecteurFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $secteur1 = new Secteur();
        $secteur1->setNomSecteur("Station service");
        $manager->persist($secteur1);

        $secteur2 = new Secteur();
        $secteur2->setNomSecteur("Pharmacie");
        $manager->persist($secteur2);

        $manager->flush();
    }
}

