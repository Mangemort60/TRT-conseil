<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\User;
use App\Form\CandidatType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class CandidatController extends AbstractController
{

        #[Route('/candidat/profil', name: 'app_candidat_profil')]
        public function add(EntityManagerInterface $entityManager, Request $request, UserInterface $user, SluggerInterface $slugger): Response
        {
            // instance de candidat
            $candidat = new Candidat();
            // creer formulaire et liaison à l'entité classe
            $form = $this->createForm(CandidatType::class, $candidat);
            // traitement du formulaire
            $form->handleRequest($request);
            // si formulaire valide
            if($form->isSubmitted() && $form->isValid()) {
                // récupère le cv
                $cv = $form->get('Cv')->getData();
                // si il y a un cv
                if($cv) {
                    $originalFilename = pathinfo($cv->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$cv->guessExtension();
                    try {
                        $cv->move(
                            $this->getParameter('cv_directory'),
                            $newFilename

                        );
                    } catch (FileException $e) {

                    }

                }

                $user->getUserIdentifier();
                $candidat->setUser($user);
                $entityManager->persist($candidat);
                $entityManager->flush($candidat);
                
                return $this->redirectToRoute('app_annonces');
            }

            return $this->render('candidat/candidat.compte.index.html.twig', [
                'form' => $form->createView()
            ]);
        }

//    #[Route('/candidat', name: 'app_candidat')]
//    public function index(Request $request, EntityManagerInterface $entityManager): Response
//    {
//        // on va chercher l'entité
//        $candidat = new Candidat();
//
//        // creation du formulaire et liaison a l'entité
//        $form = $this->createForm(CandidatType::class, $candidat);
//
//        // traitement du formulaire
//        $form->handleRequest($request);
//        if($form->isSubmitted() && $form->isValid()) {
//            $entityManager->persist($candidat);
//            $entityManager->flush($candidat);
//        }
//
//        // rendu du formulaire
//        return $this->render('candidat/candidat.compte.index.html.twig', [
//            'form' => $form->createView(),
//        ]);
//    }
}
