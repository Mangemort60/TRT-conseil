<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    #[Route('/', name: 'app_annonces')]
    public function index(EntityManagerInterface $entityManager, AnnonceRepository $annonceRepository): Response
    {
        $annonce = $annonceRepository->findAll();
        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonce ]);
    }
}
