<?php
namespace App\Controller;

use App\Entity\CategorieB;
use App\Form\CategorieBType;
use App\Repository\CategorieBRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/categorie/b')]
class CategorieBController extends AbstractController
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    #[Route('/', name: 'app_categorie_b_index', methods: ['GET'])]
    public function index(CategorieBRepository $categorieBRepository): Response
    {
        $this->denyAccessUnLessGranted('ROLE_SUPER_ADMIN');
        
        return $this->render('categorie_b/index.html.twig', [
            'categorie_bs' => $categorieBRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_categorie_b_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnLessGranted('ROLE_SUPER_ADMIN');

        $categorieB = new CategorieB();
        $form = $this->createForm(CategorieBType::class, $categorieB);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion du fichier image
            $imgFile = $form->get('img')->getData();

            if ($imgFile) {
                $originalFilename = pathinfo($imgFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imgFile->guessExtension();

                try {
                    $imgFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // handle exception if something happens during file upload
                }

                $categorieB->setImg($newFilename);
            }

            $entityManager->persist($categorieB);
            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_b_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie_b/new.html.twig', [
            'categorie_b' => $categorieB,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_b_show', methods: ['GET'])]
    public function show(CategorieB $categorieB): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        return $this->render('categorie_b/show.html.twig', [
            'categorie_b' => $categorieB,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categorie_b_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategorieB $categorieB, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN');
        $form = $this->createForm(CategorieBType::class, $categorieB);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion du fichier image
            $imgFile = $form->get('img')->getData();

            if ($imgFile) {
                $originalFilename = pathinfo($imgFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imgFile->guessExtension();

                try {
                    $imgFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // handle exception if something happens during file upload
                }

                $categorieB->setImg($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_categorie_b_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie_b/edit.html.twig', [
            'categorie_b' => $categorieB,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_categorie_b_delete', methods: ['POST'])]
    public function delete(Request $request, CategorieB $categorieB, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnLessGranted('ROLE_SUPER_ADMIN');
        
        if ($this->isCsrfTokenValid('delete'.$categorieB->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($categorieB);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_categorie_b_index', [], Response::HTTP_SEE_OTHER);
    }
}
