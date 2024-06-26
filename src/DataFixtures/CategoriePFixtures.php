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
        //Les pulls et ses sous categories
        $parent = $this->createCategorieP('Pull', null,'Tous les pulls','pull', $manager);

        $this->createCategorieP('Pull sans manches', $parent,'Tous les pulls sans manches','pull_sans_manches', $manager);
        $this->createCategorieP('Pull manches longues', $parent,'Tous les pulls manches longues','pull_manches_longues', $manager);
        $this->createCategorieP('Pull manches courtes', $parent,'Tous les pulls manches courtes','pull_manches_courtes', $manager);

        //les sweats et ses sous categories
        $parent = $this->createCategorieP('Sweat', null,'Tous les sweats','sweat', $manager);
        $this->createCategorieP('Sweat sans manches', $parent,'Tous les sweats sans manches','sweat_sans_manches', $manager);
        $this->createCategorieP('Sweat manches longues', $parent,'Tous les sweats manches longues','sweat_manches_longues', $manager);
        $this->createCategorieP('Sweat manches courtes', $parent,'Tous les sweats manches courtes','sweat_manches_courtes', $manager);

        //Les figurines et ses sous categories
        $parent = $this->createCategorieP('Figurine', null,'Toutes les figurines','figurine', $manager);
        $this->createCategorieP('Figurine en plastique', $parent,'Touts nos figurines en plastique','figurine_en_plastique', $manager);
        $this->createCategorieP('figuine porte clé', $parent,'Toutes les figurines porte clé','figurine_porte_cle', $manager);
        $this->createCategorieP('figurine en argile', $parent,'Tous les figurines en argile','figurines_en_argile', $manager);

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
