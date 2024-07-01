<?php

namespace App\Controller;

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
    
            return $this->redirectToRoute('Home');
        } 

        #[Route('/commande/confirmation', name: 'app_commande_confirmation')]
        public function success(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        // Récupérer le repository de Commande via l'EntityManager
        $commandeRepository = $entityManager->getRepository(Commande::class);
        $commande = $commandeRepository->findOneBy(['user' => $user], ['date' => 'DESC']);

        // Vérifier si une commande a été trouvée
        if (!$commande) {
            throw $this->createNotFoundException('Aucune commande trouvée pour cet utilisateur.');
        }

        // Calcul du total de la commande
        $total = 0.0;
        foreach ($commande->getLigneCommande() as $ligneCommande) {
            $total += $ligneCommande->getQuantite() * $ligneCommande->getProduit()->getPrix();
        }

        // Envoi des données au template
        return $this->render('commande/confirmation.html.twig', [
            'commande' => $commande,
            'total' => $total,
        ]);
    }
    

}
