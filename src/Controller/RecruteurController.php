<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Recruteur;
use App\Form\AnnonceType;
use App\Form\RecruteurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class RecruteurController extends AbstractController
{
    #[Route('/recruteur/monProfil', name: 'app_profil_recruteur')]
    public function afficher( EntityManagerInterface $entityManager, UserInterface $user): Response
    {

        $userId = $user->getID();
        $recruteur = $entityManager->getRepository(Recruteur::class)->findBy(['user' => $userId]);

        return $this->render('recruteur/recruteur-profil.html.twig', ['recruteurs' => $recruteur]);
    }

    #[Route('/recruteur/add', name: 'app_recruteur_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recruteur = new Recruteur();

        $form = $this->createForm(RecruteurType::class, $recruteur);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) {
            try {
                $userId = $this->getUser();
                $recruteur->setUser($userId);
                $recruteur = $form->getData();
                $entityManager->persist($recruteur);
                $entityManager->flush();
                return $this->redirectToRoute('app_profil_recruteur');
            } catch (\Exception $e) {
                $this->addFlash('error', 'votre profil a déjà été complété');
                return $this->redirectToRoute('app_recruteur_add');
            }

        } else {

            return $this->render('recruteur/completer-profil.html.twig', [
                'form' => $form->createView()
            ]);
        }


    }

    #[Route('/recruteur/ajouter_annonce', name:'app_recruteur_ajouter_annonce')]
    public function addAnnonce(Request $request, EntityManagerInterface $entityManager) : Response {

        $annonce = new Annonce();

        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData();
            $entityManager->persist($annonce);
            $entityManager->flush();
            return $this->redirectToRoute('app_annonces');
        } else {
            return $this->renderForm('annonce/ajouter.html.twig', [
                'form' => $form,
            ]);
        }
        


    }


}

