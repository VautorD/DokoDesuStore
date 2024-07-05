<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\BoutiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends AbstractController
{
    #[Route('/mon-compte', name: 'app_profile_index')]
    public function index(BoutiqueRepository $boutiqueRepository): Response
    {   
        //si le user est connecté
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // On vérifie si l'utilisateur a une boutique
        $boutique = $boutiqueRepository->findOneBy(['user' => $user]);

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'boutique' => $boutique,
        ]);
    }

    #[Route('/edit', name: 'app_user_profile_edit')]
    public function editProfile(Request $request, EntityManagerInterface $entityManager): Response
    {
        
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('app_profile_index'); 
        }

        return $this->render('user/edit_profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}