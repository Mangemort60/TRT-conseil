<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Form\CandidatType;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/annonces', name: 'app_annonces')]
    public function index(): Response
    {
        $annonces = [
            ['poste' => 'Serveur', 'lieuDeTravail' => 'Paris', 'description' => 'serveur H/F , CDI, expèrience exigée 1ans minimum'],
            ['poste' => 'Barman', 'lieuDeTravail' => 'Lille', 'description' => 'Barman H/F , CDD, expèrience exigée 3ans minimum'],
            ['poste' => 'Cuisinier', 'lieuDeTravail' => 'Bordeaux', 'description' => 'cuisinier H/F , CDI, expèrience exigée 1ans minimum'],
        ];

        return $this->render('annonces/accueil.html.twig', [
            'title' => 'Annonces',
            'annonces' => $annonces]);
    }


}