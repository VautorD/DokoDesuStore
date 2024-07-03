<?php

namespace App\Controller;

use App\Repository\BoutiqueRepository;
use App\Repository\CategorieBRepository;
use App\Repository\CategoriePRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(
        CategorieBRepository $categorieBRepository,
        ProduitRepository $produitRepository,
        BoutiqueRepository $boutiqueRepository,
        CategoriePRepository $categoriePRepository
    ): Response {
        // Trouver l'ID de la catégorie "alimentaire"
        $categorieP = $categoriePRepository->findOneByNom('alimentaire');

        if (!$categorieP) {
            throw $this->createNotFoundException('Catégorie "alimentaire" non trouvée.');
        }

        // Récupérer les produits de la catégorie "alimentaire"
        $produitsAlimentaires = $produitRepository->findByCategorieP($categorieP->getId());

        return $this->render('home/index.html.twig', [
            'categoriesB' => $categorieBRepository->findAll(),
            'boutiques' => $boutiqueRepository->findAll(),
            'produits' => $produitRepository->findAll(),
            'produitsAlimentaires' => $produitsAlimentaires,
        ]);
    }
}
