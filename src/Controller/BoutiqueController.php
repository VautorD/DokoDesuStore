<?php

namespace App\Controller;

use App\Entity\Boutique;
use App\Form\BoutiqueType;
use App\Repository\BoutiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategorieBRepository;
use App\Entity\CategorieB;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/boutique')]
class BoutiqueController extends AbstractController
{

    public function __construct( private Security $security, private SluggerInterface $slugger){}

    #[Route('/', name: 'app_boutique_index', methods: ['GET'])]
    public function index(BoutiqueRepository $boutiqueRepository): Response
    {
        return $this->render('boutique/index.html.twig', [
            'boutiques' => $boutiqueRepository->findAll(),
        ]);
    }

    #[Route('/boutique/new', name: 'app_boutique_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $boutique = new Boutique();
        $form = $this->createForm(BoutiqueType::class, $boutique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'utilisateur connecté pour le mettre dans la bdd
            $user = $this->security->getUser();
            $boutique->setUser($user);

            // Générer autoamtiquement le slug pour la bdd
            $slug = $this->slugger->slug($boutique->getNom())->lower();
            $slug = $this->makeSlugUnique($slug, $em);

            $boutique->setSlug($slug);

            $em->persist($boutique);
            $em->flush();

            return $this->redirectToRoute('app_boutique_index');
        }

        return $this->render('boutique/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //Pour eviter les slug en double dans la bdd on rajoute un nombre
    private function makeSlugUnique(string $slug, EntityManagerInterface $em): string
    {
        $originalSlug = $slug;
        $i = 1;

        while ($em->getRepository(Boutique::class)->findOneBy(['slug' => $slug])) {
            $slug = $originalSlug . '-' . $i;
            $i++;
        }

        return $slug;
    }
    
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

    #[Route('/{id}', name: 'app_boutique_show', methods: ['GET'])]
    public function show(Boutique $boutique): Response
    {
        return $this->render('boutique/show.html.twig', [
            'boutique' => $boutique,
        ]);
    }

    #[Route('/{slug}', name: 'app_boutique_template', methods: ['GET'])]
    public function templateBoutique(Request $request, BoutiqueRepository $boutiqueRepository): Response
    {

        $slug = $request->attributes->get('slug');
        $boutique = $boutiqueRepository->findOneBy(['slug' => $slug]);
        
        return $this->render('boutique/templateBoutique.html.twig', [
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
