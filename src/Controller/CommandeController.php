<?php

namespace App\Controller;

use App\Entity\Boutique;
use App\Entity\Commande;
use App\Entity\LigneCommande;
use App\Repository\ProduitRepository;
use App\Service\BoutiqueService;
use App\Service\EmailService;
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
    private $emailService;

    public function __construct(Security $security, EntityManagerInterface $entityManager, BoutiqueService $boutiqueService, EmailService $emailService)
    {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->boutiqueService = $boutiqueService;
        $this->emailService = $emailService;
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
    public function valider(SessionInterface $session, ProduitRepository $produitRepository): Response
    {
        $user = $this->security->getUser();
        $panier = $session->get('panier', []);

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if (empty($panier)) {
            return $this->redirectToRoute('app_panier_index');
        }

        try {
            //On commence la transaction
            $this->entityManager->beginTransaction();

            $commande = new Commande();
            $commande->setUser($user);
            $commande->setDate(new \DateTime());

            $total = 0.0;

            foreach ($panier as $id => $quantite) {
                $produit = $produitRepository->find($id);
                if ($produit) {

                    // On prend la boutique associée au produit
                    $boutique = $this->boutiqueService->getBoutiqueByProduit($produit);

                    if ($boutique) {
                        $commande->setBoutique($boutique);

                        // Création de la ligne de commande
                        $ligneCommande = new LigneCommande();
                        $ligneCommande->setProduit($produit);
                        $ligneCommande->setQuantite($quantite);
                        $commande->addLigneCommande($ligneCommande);

                        // Calcul du total
                        $total += $quantite * $produit->getPrix();

                        $this->entityManager->persist($ligneCommande);

                        // On envoi le mail de notification au user qui possède la boutique
                        $proprietaire = $boutique->getUser();
                        if ($proprietaire) {
                            $this->emailService->sendNewOrderNotification($proprietaire, $commande);
                        }
                    }
                }
            }

            // On envoie un mmail de confirmation avec le total au user qui a passé commande
            $this->emailService->sendConfirmationEmail($user, $total);

            $this->entityManager->persist($commande);
            $this->entityManager->flush();

            $this->entityManager->commit();

            // On efface le panier après que la commande soit validé
            $session->set('panier', []);

            return $this->redirectToRoute('app_commande_confirmation', ['id' => $commande->getId()]);
        } catch (\Exception $e) {
            // Si erreur on annule
            $this->entityManager->rollback();
            throw $e;
        }
    }

        #[Route('/commande/confirmation/{id}', name: 'app_commande_confirmation', methods: ['GET'])]
        public function success(EntityManagerInterface $entityManager, int $id): Response
        {
            $user = $this->getUser();

            // On récupère la commande par son id et l'utilisateur actuel
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

        #[Route('/commanderecue', name: 'app_commande_recue', methods: ['GET'])]
        public function historiqueBoutique(): Response
    {
        // Récupérer l'utilisateur actuel
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer toutes les boutiques associées à cet utilisateur
        $boutiques = $this->entityManager->getRepository(Boutique::class)
            ->findBy(['user' => $user]);

        // Récupérer toutes les commandes pour les boutiques de l'utilisateur
        $commandes = [];
        foreach ($boutiques as $boutique) {
            $commandesDeLaBoutique = $this->entityManager->getRepository(Commande::class)
                ->findBy(['boutique' => $boutique], ['date' => 'DESC']);
            $commandes = array_merge($commandes, $commandesDeLaBoutique);
        }

        return $this->render('commande/commanderecue.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    

}
