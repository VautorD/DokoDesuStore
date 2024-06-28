<?php

namespace App\Controller;

use App\Entity\Boutique;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function search(Request $request): Response
    {
        $term = $request->query->get('q');
       
        $repository = $this->entityManager->getRepository(Produit::class);
        $produits = $repository->findByPartialNom($term);

        $repository = $this->entityManager->getRepository(Boutique::class);
        $boutiques = $repository->findByPartialNom($term);

        return $this->render('search/results.html.twig', [
            'term' => $term,
            'produits' => $produits,
            'boutiques' => $boutiques,
        ]);
    }
}
