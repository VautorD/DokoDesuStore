<?php

namespace App\Controller;

use App\Entity\Boutique;
use App\Form\BoutiqueType;
use App\Repository\BoutiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategorieBRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Finder\Exception\AccessDeniedException;
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

    //Afficher les boutiques avec possibilité de filtrer par catégorie
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

            if ($this->isGranted('ROLE_SUPER_ADMIN')) {
                return $this->redirectToRoute('app_boutique_index', [], Response::HTTP_SEE_OTHER);
            } elseif ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_professionnel', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('boutique/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //Pour qu un professionnel modifie sa boutique
    #[Route('/professionnel/{id}', name: 'app_professionel_boutique', methods: ['GET'])]
    public function showMyBoutique(BoutiqueRepository $boutiqueRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        // Récupérer la boutique associée au user connecté
        $boutique = $boutiqueRepository->findOneBy(['user' => $user]);

        // Vérifier s il a une boutique
        if (!$boutique) {
            throw new AccessDeniedException('Vous n\'avez pas de boutique associée à votre compte.');
        }

        return $this->render('boutique/show.html.twig', [
            'boutique' => $boutique,
        ]);
    }

    #[Route('/{id}', name: 'app_boutique_show', methods: ['GET'])]
    public function show(Boutique $boutique): Response
    {
        return $this->render('boutique/show.html.twig', [
            'boutique' => $boutique,
        ]);
    }

    //Modifier
    #[Route('/{id}/edit', name: 'app_boutique_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Boutique $boutique, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BoutiqueType::class, $boutique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            if ($this->isGranted('ROLE_SUPER_ADMIN')) {
                return $this->redirectToRoute('app_boutique_index', [], Response::HTTP_SEE_OTHER);
            } elseif ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_professionnel', [], Response::HTTP_SEE_OTHER);
            }
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

    //Afficher les boutiques par leur slug
    #[Route('/Boutique/{slug}', name: 'app_boutique_template', methods: ['GET'])]
    public function templateBoutique(Request $request, BoutiqueRepository $boutiqueRepository): Response
    {

        $slug = $request->attributes->get('slug');
        $boutique = $boutiqueRepository->findOneBy(['slug' => $slug]);
        
        return $this->render('boutique/templateBoutique.html.twig', [
            'boutique' => $boutique,
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
}