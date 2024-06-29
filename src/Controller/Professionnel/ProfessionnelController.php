<?php

namespace App\Controller\Professionnel;

use App\Repository\BoutiqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfessionnelController extends AbstractController
{
    #[Route('/professionnel', name: 'app_professionnel')]
    public function index(BoutiqueRepository $boutiqueRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Vérifie si l'utilisateur a une boutique
        $boutique = $boutiqueRepository->findOneBy(['user' => $user]);

        return $this->render('professionnel/index.html.twig', [
            'user' => $user,
            'boutique' => $boutique,
        ]);
    }
}
