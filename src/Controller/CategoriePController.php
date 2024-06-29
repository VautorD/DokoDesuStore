<?php

namespace App\Controller;

use App\Entity\CategorieP;
use App\Form\CategoriePType;
use App\Repository\CategoriePRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/categorie/p')]
class CategoriePController extends AbstractController
{
    #[Route('/', name: 'app_categorie_p_index', methods: ['GET'])]
    public function index(CategoriePRepository $categoriePRepository): Response
    {
        return $this->render('categorie_p/index.html.twig', [
            'categorie_ps' => $categoriePRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categorie_p_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnLessGranted('ROLE_SUPER_ADMIN');
        $categorieP = new CategorieP();
        $form = $this->createForm(CategoriePType::class, $categorieP);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorieP);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_p_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie_p/new.html.twig', [
            'categorie_p' => $categorieP,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_categorieP_list', methods: ['GET'])]
    public function list(Request $request, CategoriePRepository $categoriePRepository): Response
    {
        
        $slug = $request->attributes->get('slug');
        $categorie_p = $categoriePRepository->findOneBy(['slug' => $slug]);

        $produits = $this->getProduitsRecursive($categorie_p);

        return $this->render('categorie_p/list.html.twig', [
            'categorie_p' => $categorie_p,
            'produits' => $produits
        ]);
    }

    private function getProduitsRecursive(CategorieP $categorieP): array
    {
        $produits = [];

        foreach ($categorieP->getProduits() as $produit) {
            $produits[] = $produit;
        }

        foreach ($categorieP->getCategoriePs() as $sousCategorieP) {
            $produits = array_merge($produits, $this->getProduitsRecursive($sousCategorieP));
        }

        return $produits;
    }

    #[Route('/{id}', name: 'app_categorie_p_show', methods: ['GET'])]
    public function show(CategorieP $categorieP): Response
    {
        $this->denyAccessUnLessGranted('ROLE_SUPER_ADMIN');
        return $this->render('categorie_p/show.html.twig', [
            'categorie_p' => $categorieP,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorie_p_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieP $categorieP, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnLessGranted('ROLE_SUPER_ADMIN');
        $form = $this->createForm(CategoriePType::class, $categorieP);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_p_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie_p/edit.html.twig', [
            'categorie_p' => $categorieP,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_p_delete', methods: ['POST'])]
    public function delete(Request $request, CategorieP $categorieP, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnLessGranted('ROLE_SUPER_ADMIN');
        if ($this->isCsrfTokenValid('delete'.$categorieP->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($categorieP);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categorie_p_index', [], Response::HTTP_SEE_OTHER);
    }
}
