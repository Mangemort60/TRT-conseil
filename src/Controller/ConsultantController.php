<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Candidature;
use App\Entity\User;
use App\Repository\AnnonceRepository;
use App\Repository\CandidatureRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ConsultantController extends AbstractController
{
    #[Route('/afficher_annonces', name: 'app_consultant_annonces_afficher')]
    public function afficherAnnonces(EntityManagerInterface $entityManager, AnnonceRepository $annonceRepository): Response
    {
        $annonce = $annonceRepository->findAll();
        return $this->render('consultant/consultant-annonces-afficher.html.twig', [
            'annonces' => $annonce ]);
    }

    #[Route('/afficher_comptes', name: 'app_afficher_comptes')]
    public function afficheCompte( UserRepository $userRepository): Response
    {
        // affiche compte à valider
        $users = $userRepository->findAll();

        return $this->render('consultant/afficher-comptes.html.twig' ,[
            'users' => $users
        ]);



    }

    #[Route('/valider_comptes/{id}', name: 'app_valider_comptes')]
    public function validerCompte(EntityManagerInterface $entityManager, User $user): Response
    {
        // affiche compte à valider

        $user->setActive('1');
        $entityManager->persist($user);
        $entityManager->flush();        
        return $this->redirectToRoute('app_afficher_comptes');



    }

    #[Route('/desactiver_comptes/{id}', name: 'app_desactiver_comptes')]
    public function desactiverCompte(EntityManagerInterface $entityManager, User $user): Response
    {

        $user->setActive('0');

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_afficher_comptes');



    }

    #[Route('/valider_annonce/{id}', name: 'app_valider_annonce')]
    public function validerAnnonce(EntityManagerInterface $entityManager, Annonce $annonce): Response
    {
        $annonce->setActive('1');
        $entityManager->persist($annonce);
        $entityManager->flush();
        return $this->redirectToRoute('app_consultant_annonces_afficher');

    }

    #[Route('/afficher_candidature', name: 'app_afficher_candidature')]
    public function afficherCandidature(EntityManagerInterface $entityManager, CandidatureRepository $candidatureRepository): Response
    {

        $candidatures = $candidatureRepository->findAll();

        return $this->render('consultant/consultant-candidature-afficher.html.twig', ['candidatures' => $candidatures]);

    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/valider_candidature/{id}', name: 'app_valider_candidature')]
    public function validerCandidature(EntityManagerInterface $entityManager, Candidature $candidature, MailerInterface $mailer): Response
    {

        // valide la candidature
        $candidature->setActive('1');
        $entityManager->persist($candidature);
        $entityManager->flush();

        // on recupere nom , prenom , cv du candidat


        // envoyer email au recruteur

        $email = (new Email())
            ->from('hahaddaoui@gmail.com')
            ->to('hahaddaoui@gmail.com')
            ->subject('nouvelle candidature')
            ->text('Bonjour, nous avons une nouvelle candidature a vous proposer...');

        $mailer->send($email);

        return $this->redirectToRoute('app_afficher_candidature', );



    }
}
