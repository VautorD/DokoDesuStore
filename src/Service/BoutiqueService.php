<?php

// src/Service/BoutiqueService.php
namespace App\Service;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;

class BoutiqueService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getBoutiqueByProduit(Produit $produit)
    {
        
        return $produit->getBoutique();
    }
}
