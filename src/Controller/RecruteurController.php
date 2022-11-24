<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Recruteur;
use App\Form\AnnonceType;
use App\Form\RecruteurType;
use App\Repository\CandidatureRepository;
use App\Repository\RecruteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
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
    public function addAnnonce(Request $request, EntityManagerInterface $entityManager, UserInterface $user, RecruteurRepository $recruteurRepository) : Response {

        $annonce = new Annonce();

        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $form->getData();

                $userId = $user->getID();
                $recruteur = $recruteurRepository->findOneBySomeField($userId);
                $recruteurNom = $recruteur->getNomEntreprise();
                $annonce->setRecruteur($recruteur);
                $annonce->setEntreprise($recruteurNom);
                $entityManager->persist($annonce);
                $entityManager->flush();
                return $this->redirectToRoute('app_annonces');

            } else {
                return $this->renderForm('annonce/ajouter.html.twig', [
                    'form' => $form,
                ]);
            }
        } catch (\Throwable $t) {

            $this->addFlash('errorProfilRecruteur', 'Veuillez completer votre profil afin de poster une annonce');
            return $this->redirectToRoute('app_recruteur_ajouter_annonce');
        }

        


    }

    #[Route('/recruteur/afficher_candidature', name:'app_recruteur_afficher_candidature')]
    public function afficherCandidature(EntityManagerInterface $entityManager, UserInterface $user, CandidatureRepository $candidatureRepository, RecruteurRepository $recruteurRepository) : Response {

        // trouve le recruteur connecté
    $recruteur = $recruteurRepository->findOneBySomeField($user);
        // trouve les candidatures liée au recruteur
    $candidatures = $candidatureRepository->findBy(['recruteur' => $recruteur]);



    return $this->render('recruteur/recruteur-candidature-afficher.twig', ['candidatures' => $candidatures]);
    }
}

