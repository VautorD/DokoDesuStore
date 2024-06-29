<?php

namespace App\Controller;

use App\Repository\BoutiqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends AbstractController
{
    #[Route('/mon-compte', name: 'app_profile_index')]
    public function index(BoutiqueRepository $boutiqueRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Vérifie si l'utilisateur a une boutique
        $boutique = $boutiqueRepository->findOneBy(['user' => $user]);

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'boutique' => $boutique,
        ]);
    }
}