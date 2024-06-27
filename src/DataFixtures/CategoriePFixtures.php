<?php

namespace App\DataFixtures;

use App\Entity\CategorieP;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoriePFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger){}

    public function load(ObjectManager $manager): void
    {
        //Les vetements et ses sous categories
        $parent = $this->createCategorieP('Vêtements', null,'Tous les vêtements','vetements', $manager);

        $this->createCategorieP('Pull', $parent,'Tous les pulls','pulls', $manager);
        $this->createCategorieP('Sweat', $parent,'Tous les sweats','sweats', $manager);
        $this->createCategorieP('Tee-shirt', $parent,'Tous les tee-shirts','tee-shirt', $manager);

        //les cosplay et ses sous categories
        $parent = $this->createCategorieP('Cosplay', null,'Tous les cosplays','cosplays', $manager);
        $this->createCategorieP('Perruque', $parent,'Toutes les perruques','perruques', $manager);
        $this->createCategorieP('Lentilles', $parent,'Tous les lentilles','lentilles', $manager);
        $this->createCategorieP('Masques', $parent,'Tous les masques','marques', $manager);

        //Les Librairies et ses sous categories
        $parent = $this->createCategorieP('Libraires', null,'Toutes les livres','librairies', $manager);
        $this->createCategorieP('Mangas', $parent,'Touts les mangas','manga', $manager);
        $this->createCategorieP('Romans', $parent,'Toutes les romans','roman', $manager);
        $this->createCategorieP('Nouvelles', $parent,'Tous les nouvelles','nouvelles', $manager);

        //Les produits dérivés et ses sous categories
        $parent = $this->createCategorieP('Produits dérivés', null,'Toutes les produits dérivés','produitsD', $manager);
        $this->createCategorieP('Posters', $parent,'Touts les posters','posters', $manager);
        $this->createCategorieP('Porte clés', $parent,'Toutes les porte clés','porteC', $manager);
        $this->createCategorieP('figurines', $parent,'Tous les figurines','figurines', $manager);

        //Alimentaire et ses sous catégories
        $parent = $this->createCategorieP('Alimentaire', null,'Tous les aliments','alimentaire', $manager);
        $this->createCategorieP('Boisson', $parent,'Toutes les boissons','boisson', $manager);
        $this->createCategorieP('Nouilles', $parent,'Toutes les nouilles','nouilles', $manager);
        $this->createCategorieP('Mocchi', $parent,'Tous nos mocchis','mocchi', $manager);

        $manager->flush();
    }

    public function createCategorieP(string $nom, CategorieP $parent = null, string $description, string $img, ObjectManager $manager){
        
        $categoryP = new CategorieP();
        $categoryP->setNom($nom);
        $categoryP->setParent($parent);
        $categoryP->setDescription($description);
        $categoryP->setSlug($this->slugger->slug($categoryP->getNom())->lower());
        $categoryP->setImg($img);
        $manager->persist($categoryP);

        return $categoryP;
    }
}
