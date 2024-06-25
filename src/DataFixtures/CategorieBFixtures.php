<?php

namespace App\DataFixtures;

use App\Entity\CategorieB;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieBFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categoryB = new CategorieB();
        $categoryB->setNom('Vetements');
        $categoryB->setDescription('Tous les vetements');
        $categoryB->setSlug('vetements');
        $categoryB->setImg('Vetements');
        $manager->persist($categoryB);

        $categoryB = new CategorieB();
        $categoryB->setNom('Cosplay');
        $categoryB->setDescription('Tous les cosplay');
        $categoryB->setSlug('cosplay');
        $categoryB->setImg('cosplay');
        $manager->persist($categoryB);

        $categoryB = new CategorieB();
        $categoryB->setNom('Librairie');
        $categoryB->setDescription('Tous nos livres');
        $categoryB->setSlug('librairie');
        $categoryB->setImg('librairie');
        $manager->persist($categoryB);

        $categoryB = new CategorieB();
        $categoryB->setNom('Produits dérivés');
        $categoryB->setDescription('Tous les produits dérivés');
        $categoryB->setSlug('produits-derives');
        $categoryB->setImg('produitD');
        $manager->persist($categoryB);

        $categoryB = new CategorieB();
        $categoryB->setNom('Alimentaire');
        $categoryB->setDescription('Tous les produits alimentaire');
        $categoryB->setSlug('alimentaire');
        $categoryB->setImg('alimentaire');
        $manager->persist($categoryB);

        $manager->flush();
    }
}
