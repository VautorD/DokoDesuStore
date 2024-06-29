<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\BoutiqueRepository;
use App\Repository\CategoriePRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    public function __construct(private SluggerInterface $slugger){}

    #[Route('/', name: 'app_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, BoutiqueRepository $boutiqueRepository): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'utilisateur connecté
            $user = $this->getUser();

            // Trouver la boutique associée à cet utilisateur
            $boutique = $boutiqueRepository->findOneBy(['user' => $user]);

            // Assurez-vous que $boutique est définie et non null
            if (!$boutique) {
                throw new \RuntimeException('User does not have an associated boutique.');
            }

            // Assigner la boutique au produit
            $produit->setBoutique($boutique);

            $slug = $this->slugger->slug($produit->getNom())->lower();
            $slug = $this->makeSlugUnique($slug, $entityManager);

            $produit->setSlug($slug);

            $entityManager->persist($produit);
            $entityManager->flush();

            if ($this->isGranted('ROLE_SUPER_ADMIN')) {
                    return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
                } elseif ($this->isGranted('ROLE_ADMIN')) {
                    return $this->redirectToRoute('app_produit_boutique', [], Response::HTTP_SEE_OTHER);
                }
            }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    //Pour eviter les slug en double dans la bdd on rajoute un nombre
    private function makeSlugUnique(string $slug, EntityManagerInterface $em): string
    {
        $originalSlug = $slug;
        $i = 1;

        while ($em->getRepository(Produit::class)->findOneBy(['slug' => $slug])) {
            $slug = $originalSlug . '-' . $i;
            $i++;
        }

        return $slug;
    }

    #[Route('/produit/list', name: 'app_produit_boutique', methods: ['GET'])]
    public function listByBoutique(ProduitRepository $produitRepository, BoutiqueRepository $boutiqueRepository): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Trouver la boutique associée à cet utilisateur
        $boutique = $boutiqueRepository->findOneBy(['user' => $user]);

        // Assurez-vous que $boutique est définie et non null
        if (!$boutique) {
            throw new \RuntimeException('User does not have an associated boutique.');
        }

        // Récupérer les produits de cette boutique
        $produits = $produitRepository->findBy(['boutique' => $boutique]);

        return $this->render('produit/list.html.twig', [
            'boutique' => $boutique,
            'produits' => $produits,
        ]);
    }

    #[Route('/all', name: 'app_produit_all', methods: ['GET'])]
    public function all(ProduitRepository $produitRepository, CategoriePRepository $categoriePRepository, Request $request): Response
    {
        $categorieId = $request->query->get('categorie');
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

            if ($this->isGranted('ROLE_SUPER_ADMIN')) {
                return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
            } elseif ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_produit_boutique', [], Response::HTTP_SEE_OTHER);
            }
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