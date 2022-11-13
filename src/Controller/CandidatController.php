<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Form\CandidatType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;



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


    

}
