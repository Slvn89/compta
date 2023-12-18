<?php // src/Controller/SecteurController.php

namespace App\Controller;

use App\Entity\Secteur;
use App\Form\SecteurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecteurController extends AbstractController
{
    #[Route('/choix-secteur', name: 'choix_secteur')]
    public function choixSecteur(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérez la liste des secteurs depuis la base de données
        $secteurs = $entityManager->getRepository(Secteur::class)->findAll();

        // Choisissez le premier secteur comme exemple, vous pouvez ajuster cela en fonction de votre logique
        $exampleSecteur = $secteurs[0];

        // Créez le formulaire en utilisant le SecteurType et passez le nom du secteur
        $form = $this->createForm(SecteurType::class, null, [
            'secteur_nom' => $exampleSecteur->getNom(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vous pouvez traiter les données du formulaire ici
            // Par exemple, récupérez le secteur sélectionné
            $selectedSecteur = $form->getData();
            
            // Assurez-vous que $selectedSecteur contient l'objet Secteur avec son ID
            $secteurId = $selectedSecteur->getId();
        
            // Redirigez en fournissant le paramètre "secteurId"
            return $this->redirectToRoute('choix_fournisseur', ['secteurId' => $secteurId]);
        }

        return $this->render('secteur/choix_secteur.html.twig', [
            'secteurs' => $secteurs,
            'form' => $form->createView(),
        ]);
    }
}
