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
    public function index(CategorieBRepository $categorieBRepository, ProduitRepository $produitRepository, BoutiqueRepository $boutiqueRepository, CategoriePRepository $categoriePRepository): Response {
        
        $categorieAlimentaire = $categoriePRepository->findOneBy(['nom' => 'Alimentaire']);

        if (!$categorieAlimentaire) {
            throw $this->createNotFoundException('Catégorie "Alimentaire" non trouvée.');
        }

        // On cherche les sous-catégories de Alimentaire
        $sousCategories = $categoriePRepository->findBy(['parent' => $categorieAlimentaire]);

        // On ressort les produits de ces sous-catégories
        $produitsAlimentaires = $produitRepository->findBy(['categorieP' => $sousCategories]);

        return $this->render('home/index.html.twig', [
            'categoriesB' => $categorieBRepository->findAll(),
            'boutiques' => $boutiqueRepository->findAll(),
            'produits' => $produitRepository->findAll(),
            'produitsAlimentaires' => $produitsAlimentaires,
        ]);
    }

}
