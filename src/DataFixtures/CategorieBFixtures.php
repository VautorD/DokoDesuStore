<?php

namespace App\DataFixtures;

use App\Entity\CategorieB;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategorieBFixtures extends Fixture
{
    //SluggerInterface qui provient du composant "String" et nous permet de faire les slug automatiquement
    public function __construct(private SluggerInterface $slugger) {}
    
    public function load(ObjectManager $manager): void
    {
        //Les boutique de vetements
        $this->createCategorieB('Vetements','Tous les vetements','Vetements', $manager);

        //Les boutiques de cosplay
        $this->createCategorieB('Cosplay','Tous les cosplay','cosplay', $manager);

        //Les boutiques qui sont des librairie
        $this->createCategorieB('Librairie','Tous nos livres','librairie', $manager);

        //Les boutiques qui proposent des produits dérivés
        $this->createCategorieB('Produits dérivés','Tous les produits dérivés','produitD', $manager);
        $this->createCategorieB('Alimentaire','Tous les produits alimentaire','alimentaire', $manager);

        $manager->flush();
    }

    public function createCategorieB(string $nom, string $description, string $img, ObjectManager $manager){
        
        $categoryB = new CategorieB();
        $categoryB->setNom($nom);
        $categoryB->setDescription($description);
        $categoryB->setSlug($this->slugger->slug($categoryB->getNom())->lower());
        $categoryB->setImg($img);
        $manager->persist($categoryB);
    }
}
