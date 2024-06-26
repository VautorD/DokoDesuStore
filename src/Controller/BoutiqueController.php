<?php

namespace App\Controller;

use App\Entity\Boutique;
use App\Form\BoutiqueType;
use App\Repository\BoutiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategorieBRepository;
use App\Entity\CategorieB;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/boutique')]
class BoutiqueController extends AbstractController
{
    #[Route('/', name: 'app_boutique_index', methods: ['GET'])]
    public function index(BoutiqueRepository $boutiqueRepository): Response
    {
        return $this->render('boutique/index.html.twig', [
            'boutiques' => $boutiqueRepository->findAll(),
        ]);
    }

    #[Route('/template/{id}', name: 'app_boutique_template', methods: ['GET'])]
    public function templateBoutique(Boutique $boutique): Response
    {

        return $this->render('boutique/templateBoutique.html.twig', [
            'boutique' => $boutique,
        ]);
    }

    // #[Route('/all', name: 'app_boutique_all', methods: ['GET', 'POST'])]
    // public function allBoutique(BoutiqueRepository $boutiqueRepository,CategorieBRepository $categorieBRepository): Response
    // {
    //     return $this->render('boutique/allBoutique.html.twig', [
    //         'boutiques' => $boutiqueRepository->findAll(),
    //         'categorie_bs' => $categorieBRepository->findAll()
    //     ]);
    // }
    
    #[Route('/all', name: 'app_boutique_all', methods: ['GET', 'POST'])]
    public function allBoutique(BoutiqueRepository $boutiqueRepository, CategorieBRepository $categorieBRepository, Request $request): Response
    {
        $categorieId = $request->query->get('categorie');

        if ($categorieId) {
            $boutiques = $boutiqueRepository->findBy(['categorieB' => $categorieId]);
        } else {
            $boutiques = $boutiqueRepository->findAll();
        }

        return $this->render('boutique/allBoutique.html.twig', [
            'boutiques' => $boutiques,
            'categorie_bs' => $categorieBRepository->findAll()
        ]);
    }

    #[Route('/new', name: 'app_boutique_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $boutique = new Boutique();
        $form = $this->createForm(BoutiqueType::class, $boutique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($boutique);
            $entityManager->flush();

            return $this->redirectToRoute('app_boutique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('boutique/new.html.twig', [
            'boutique' => $boutique,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_boutique_show', methods: ['GET'])]
    public function show(Boutique $boutique): Response
    {
        return $this->render('boutique/show.html.twig', [
            'boutique' => $boutique,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_boutique_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Boutique $boutique, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BoutiqueType::class, $boutique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_boutique_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('boutique/edit.html.twig', [
            'boutique' => $boutique,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_boutique_delete', methods: ['POST'])]
    public function delete(Request $request, Boutique $boutique, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $boutique->getId(), $request->request->get('_token'))) {
            $entityManager->remove($boutique);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_boutique_index', [], Response::HTTP_SEE_OTHER);
    }
}
