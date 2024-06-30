<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\CategoriePRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/all', name: 'app_produit_all', methods: ['GET'])]
    public function all(ProduitRepository $produitRepository, CategoriePRepository $categoriePRepository, Request $request): Response
    {
        $categorieId = $request->query->get('categorie');
        $sort = $request->query->get('sort', 'price_asc');
        $produits = [];

        if ($categorieId) {
            $categorie = $categoriePRepository->find($categorieId);
            if ($categorie->getParent() === null) {
                // C'est une catégorie mère, obtenir tous les produits dans cette catégorie et ses enfants
                $childCategories = $categoriePRepository->findBy(['parent' => $categorieId]);
                $childCategoryIds = array_map(fn($cat) => $cat->getId(), $childCategories);
                $produits = $produitRepository->findBy(['categorieP' => array_merge([$categorieId], $childCategoryIds)]);
            } else {
                // C'est une catégorie enfant, obtenir les produits dans cette catégorie uniquement
                $produits = $produitRepository->findBy(['categorieP' => $categorieId]);
            }
        } else {
            $produits = $produitRepository->findAll();
        }

        switch ($sort) {
            case 'price_asc':
                usort($produits, fn($a, $b) => $a->getPrix() <=> $b->getPrix());
                break;
            case 'price_desc':
                usort($produits, fn($a, $b) => $b->getPrix() <=> $a->getPrix());
                break;
            case 'alpha_asc':
                usort($produits, fn($a, $b) => strcmp($a->getNom(), $b->getNom()));
                break;
            case 'alpha_desc':
                usort($produits, fn($a, $b) => strcmp($b->getNom(), $a->getNom()));
                break;
        }

        return $this->render('produit/all.html.twig', [
            'produits' => $produits,
            'categorie_ps' => $categoriePRepository->findAll(),
        ]);
    }




    #[Route('/{slug}', name: 'app_produit_details', methods: ['GET'])]
    public function details(Request $request, ProduitRepository $produitRepository): Response
    {
        
        $slug = $request->attributes->get('slug');
        $produit = $produitRepository->findOneBy(['slug' => $slug]);
        $boutique = $produit->getBoutique();

        return $this->render('produit/detail.html.twig', [
            'produit' => $produit,
            'boutique' => $boutique,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }

}