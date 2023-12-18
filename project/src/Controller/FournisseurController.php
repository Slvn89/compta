<?php

// src/Controller/FournisseurController.php

namespace App\Controller;

use App\Entity\Fournisseur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FournisseurController extends AbstractController
{
    #[Route('/choix-fournisseur/{secteurId}', name: 'choix_fournisseur')]
    public function choixFournisseur(int $secteurId, EntityManagerInterface $entityManager): Response
    {
        // Récupérez la liste des fournisseurs pour le secteur choisi
        $fournisseurs = $entityManager->getRepository(Fournisseur::class)->findBy(['secteur' => $secteurId]);

        return $this->render('fournisseur/choix_fournisseur.html.twig', [
            'fournisseurs' => $fournisseurs,
        ]);
    }
}
