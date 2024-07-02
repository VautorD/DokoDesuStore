<?php

namespace App\Controller;

use App\Entity\Boutique;
use App\Entity\Commande;
use App\Entity\LigneCommande;
use App\Repository\ProduitRepository;
use App\Service\BoutiqueService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class CommandeController extends AbstractController
{
    private $security;
    private $entityManager;
    private $boutiqueService;

    public function __construct(Security $security, EntityManagerInterface $entityManager, BoutiqueService $boutiqueService)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->boutiqueService = $boutiqueService;
    }

    #[Route('/boutique/commandes/{slug}', name: 'app_boutique_commandes', methods: ['GET'])]
    public function commandesBoutique(string $slug): Response
    {
        $boutique = $this->entityManager->getRepository(Boutique::class)->findOneBy(['slug' => $slug]);

        if (!$boutique) {
            return $this->redirectToRoute('app_login');
        }

        $commandeRepository = $this->entityManager->getRepository(Commande::class);
        $commandes = $commandeRepository->findBy(['boutique' => $boutique], ['date' => 'DESC']);

        return $this->render('boutique/commandes.html.twig', [
            'boutique' => $boutique,
            'commandes' => $commandes,
        ]);
    }

    #[Route('/valider', name: 'app_commande_valider', methods: ['GET'])]
    public function valider(SessionInterface $session, ProduitRepository $produitRepository)
    {
        $user = $this->security->getUser();
        $panier = $session->get('panier', []);
    
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
    
        if (empty($panier)) {
            return $this->redirectToRoute('app_panier_index');
        }
    
        $this->entityManager->beginTransaction();
    
            $commande = new Commande();
            $commande->setUser($user);
            $commande->setDate(new \DateTime());
    
            foreach ($panier as $id => $quantite) {
                $produit = $produitRepository->find($id);
                if ($produit) {
                    $boutique = $this->boutiqueService->getBoutiqueByProduit($produit);
    
                    if ($boutique) {
                        $commande->setBoutique($boutique);
    
                        $ligneCommande = new LigneCommande();
                        $ligneCommande->setProduit($produit);
                        $ligneCommande->setQuantite($quantite);
                        $commande->addLigneCommande($ligneCommande);
    
                        $this->entityManager->persist($ligneCommande);
                    }
                }
            }
    
            $this->entityManager->persist($commande);
            $this->entityManager->flush(); // Validation de la transaction
    
            $session->remove('panier');
    
            $this->entityManager->commit(); // Confirmation de la transaction
    
            return $this->redirectToRoute('app_commande_confirmation', ['id' => $commande->getId()]);
        } 

        #[Route('/commande/confirmation/{id}', name: 'app_commande_confirmation', methods: ['GET'])]
        public function success(EntityManagerInterface $entityManager, int $id): Response
        {
            $user = $this->getUser();

            // Récupérer la commande par son id et l'utilisateur actuel
            $commandeRepository = $entityManager->getRepository(Commande::class);
            $commande = $commandeRepository->findOneBy(['id' => $id, 'user' => $user]);

            $total = 0.0;
            foreach ($commande->getLigneCommande() as $ligneCommande) {
                $total += $ligneCommande->getQuantite() * $ligneCommande->getProduit()->getPrix();
            }

            return $this->render('commande/confirmation.html.twig', [
                'commande' => $commande,
                'total' => $total,
            ]);
        }


        #[Route('/historique', name: 'app_commande_historique', methods: ['GET'])]
        public function historique(): Response
        {
            $user = $this->getUser();

            if (!$user) {
                return $this->redirectToRoute('app_login');
            }

            $commandeRepository = $this->entityManager->getRepository(Commande::class);
            $commandes = $commandeRepository->findBy(['user' => $user], ['date' => 'DESC']);

            return $this->render('commande/historique.html.twig', [
                'commandes' => $commandes,
            ]);
        }

    
    

}
