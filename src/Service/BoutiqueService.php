<?php

namespace App\Service;

use App\Entity\Produit;
use App\Entity\User;
use App\Repository\BoutiqueRepository;
use Doctrine\ORM\EntityManagerInterface;

class BoutiqueService
{
    private $boutiqueRepository;

    public function getBoutiqueByProduit(Produit $produit)
    {
        
        return $produit->getBoutique();
    }

    public function getBoutiqueByUser(User $user)
    {
        return $this->boutiqueRepository->findOneBy(['owner' => $user]);
    }
}
