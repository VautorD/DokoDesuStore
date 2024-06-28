<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/panier')]
class PanierController extends AbstractController
{
    #[Route('/', name: 'app_panier_index', methods: ['GET'])]
    public function index(SessionInterface $session, ProduitRepository $produitRepository): Response
    {
        $panier = $session->get('panier');

        $data= [];
        $total = 0;

        if ($panier !== null){
            foreach($panier as $id => $quantite){
                        $produit = $produitRepository->find($id);
                        $data[] = [
                            'produit' => $produit,
                            'quantite' => $quantite
                        ];
                        $total += $produit->getPrix() * $quantite;
                    }
        }
        return $this->render('panier/index.html.twig', [
            'data' => $data,
            'total' => $total
        ]);
    }


    #[Route('/add/{id}', name: 'app_panier_add', methods: ['GET'])]
    public function add(Produit $produit, SessionInterface $session): Response
    {
        $id = $produit->getId();
        $panier = $session->get('panier', []);

        if(empty($panier[$id])){
            $panier[$id] = 1;
        }else{
            $panier[$id]++;
        }

        $session->set('panier', $panier);
        return $this->redirectToRoute('app_panier_index');
    } 

    #[Route('/remove/{id}', name: 'app_panier_remove', methods: ['GET'])]
    public function remove(Produit $produit, SessionInterface $session): Response
    {
        $id = $produit->getId();
        $panier = $session->get('panier', []);

        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        }

        $session->set('panier', $panier);
        return $this->redirectToRoute('app_panier_index');
    }
    #[Route('/delete/{id}', name: 'app_panier_delete', methods: ['GET'])]
    public function delete(Produit $produit, SessionInterface $session): Response
    {
        $id = $produit->getId();
        $panier = $session->get('panier', []);

        if(!empty($panier[$id])){
            
            unset($panier[$id]);
        }

        $session->set('panier', $panier);
        return $this->redirectToRoute('app_panier_index');
    }

    #[Route('/vider', name: 'app_panier_vider', methods: ['GET'])]
    public function vider(SessionInterface $session): Response
    {
        $session->remove('panier');
        return $this->redirectToRoute('app_panier_index');
    }

}