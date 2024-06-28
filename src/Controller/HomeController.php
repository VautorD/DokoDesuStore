<?php

namespace App\Controller;

use App\Repository\BoutiqueRepository;
use App\Repository\CategorieBRepository;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function index(CategorieBRepository $categorieBRepository, ProduitRepository $produitRepository, BoutiqueRepository $boutiqueRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'categoriesB' => $categorieBRepository->findBy([], ['id' => 'asc']),
            'boutiques' => $boutiqueRepository->findBy([], ['id' => 'asc']),
            'produits' => $produitRepository->findBy([], ['id' => 'asc'])
        ]);
    }
}
