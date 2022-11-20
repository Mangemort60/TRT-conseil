<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Candidat;
use App\Entity\Candidature;
use App\Entity\User;
use App\Form\CandidatType;
use App\Repository\CandidatRepository;
use App\Repository\CandidatureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;


class CandidatController extends AbstractController
{
    #[Route('/candidat/monProfil', name: 'app_profil_candidat')]
    public function afficher( EntityManagerInterface $entityManager, UserInterface $user): Response
    {

        $userId = $user->getID();
        $candidat = $entityManager->getRepository(Candidat::class)->findBy(['user' => $userId]);

        return $this->render('candidat/candidat-profil.html.twig', ['candidats' => $candidat]);
    }


    #[Route('/candidat/add', name: 'app_candidat_add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $candidat = new Candidat();

        $form = $this->createForm(CandidatType::class, $candidat);
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()) {

                $userId = $this->getUser();
                $candidat->setUser($userId);
                $candidat = $form->getData();
                $entityManager->persist($candidat);
                $entityManager->flush();
                return $this->redirectToRoute('app_profil_candidat');


        } else {

            return $this->render('candidat/completer-profil.html.twig', [
                'form' => $form->createView()
            ]);
        }


    }

    #[Route('/candidat/postuler/{id}', name: 'app_candidat_postuler')]
    public function postuler(EntityManagerInterface $entityManager,CandidatRepository $candidatRepository, UserInterface $user, Annonce $annonce, CandidatureRepository $candidatureRepository): Response
    {

            // user ID du user connecté
            $userId = $user->getID();

            // récupère détail de l'annonce
            $poste = $annonce->getPoste();
            $lieuDeTravail = $annonce->getLieuDeTravail();
            $description = $annonce->getLieuDeTravail();
            $recruteur = $annonce->getRecruteur();

            // candidat connecté
            $candidatLoggedIn = $candidatRepository->findOneBySomeField($userId);

            if ($candidatLoggedIn) {
                
                $candidatNom = $candidatLoggedIn->getNom();
                $candidatPrenom = $candidatLoggedIn->getPrenom();
                $candidatEmail = $user->getUserIdentifier();
                $candidatCv = $candidatLoggedIn->getCvName();

                // nouvelle candidature
                $candidature = new Candidature();
                $candidature->setNom($candidatNom);
                $candidature->setPrenom($candidatPrenom);
                $candidature->setPoste($poste);
                $candidature->setLieuDeTravail($lieuDeTravail);
                $candidature->setDescription($description);
                $candidature->setCandidatEmail($candidatEmail);
                $candidature->setRecruteur($recruteur);
                $candidature->setCv($candidatCv);

                $entityManager->persist($candidature);
                $entityManager->flush();

            } else {
                $this->addFlash('error', 'Veuillez devez compléter votre profil afin de pouvoir postuler à une annonce');
                return $this->redirectToRoute('app_annonces');
            }





        return $this->redirectToRoute('app_annonces');
    }



    

}
