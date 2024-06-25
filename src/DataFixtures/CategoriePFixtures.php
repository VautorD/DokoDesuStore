<?php

namespace App\DataFixtures;

use App\Entity\CategorieP;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoriePFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //Les pulls et ses sous categories
        $parent = new CategorieP();
        $parent->setNom('Pull');
        $parent->setDescription('Tous les pulls');
        $parent->setSlug('pull');
        $parent->setImg('pull');
        $manager->persist($parent);

        $categoryP = new CategorieP();
        $categoryP->setNom('Pull sans manches');
        $categoryP->setParent($parent);
        $categoryP->setDescription('Tous les pulls sans manches');
        $categoryP->setSlug('pull-sans-manches');
        $categoryP->setImg('pull_sans_manches');
        $manager->persist($categoryP);

        $categoryP = new CategorieP();
        $categoryP->setNom('Pull manches longues');
        $categoryP->setParent($parent);
        $categoryP->setDescription('Tous les pulls manches longues');
        $categoryP->setSlug('pull-manches-longues');
        $categoryP->setImg('pull_manches_longues');
        $manager->persist($categoryP);

        $categoryP = new CategorieP();
        $categoryP->setNom('Pull manches courtes');
        $categoryP->setParent($parent);
        $categoryP->setDescription('Tous les pulls manches courtes');
        $categoryP->setSlug('pull-manches-courtes');
        $categoryP->setImg('pull_manches_courtes');
        $manager->persist($categoryP);

        //les sweats et ses sous categories
        $parent = new CategorieP();
        $parent->setNom('Sweat');
        $parent->setDescription('Tous les sweats');
        $parent->setSlug('sweats');
        $parent->setImg('sweat');
        $manager->persist($parent);

        $categoryP = new CategorieP();
        $categoryP->setNom('Sweat sans manches');
        $categoryP->setParent($parent);
        $categoryP->setDescription('Tous les sweats sans manches');
        $categoryP->setSlug('sweat-sans-manches');
        $categoryP->setImg('sweat_sans_manches');
        $manager->persist($categoryP);

        $categoryP = new CategorieP();
        $categoryP->setNom('Sweat manches longues');
        $categoryP->setParent($parent);
        $categoryP->setDescription('Tous les sweats manches longues');
        $categoryP->setSlug('sweat-manches-longues');
        $categoryP->setImg('sweat_manches_longues');
        $manager->persist($categoryP);

        $categoryP = new CategorieP();
        $categoryP->setNom('Sweat manches courtes');
        $categoryP->setParent($parent);
        $categoryP->setDescription('Tous les sweats manches courtes');
        $categoryP->setSlug('sweat-manches-courtes');
        $categoryP->setImg('sweat_manches_courtes');
        $manager->persist($categoryP);

        //Les figurines et ses sous categories
        $parent = new CategorieP();
        $parent->setNom('Figurine');
        $parent->setDescription('Toutes les figurines');
        $parent->setSlug('figurine');
        $parent->setImg('figurine');
        $manager->persist($parent);

        $categoryP = new CategorieP();
        $categoryP->setNom('Figurine en plastique');
        $categoryP->setParent($parent);
        $categoryP->setDescription('Touts nos figurines en plastique');
        $categoryP->setSlug('figurine-en-plastique');
        $categoryP->setImg('figurine_en_plastique');
        $manager->persist($categoryP);

        $categoryP = new CategorieP();
        $categoryP->setNom('figuine porte clé');
        $categoryP->setParent($parent);
        $categoryP->setDescription('Toutes les figurines porte clé');
        $categoryP->setSlug('figurine-porte-cle');
        $categoryP->setImg('figurine_porte_cle');
        $manager->persist($categoryP);

        $categoryP = new CategorieP();
        $categoryP->setNom('figurine en argile');
        $categoryP->setParent($parent);
        $categoryP->setDescription('Tous les figurines en argile');
        $categoryP->setSlug('figurine-en-argile');
        $categoryP->setImg('figurines_en_argile');
        $manager->persist($categoryP);

        //Alimentaire et ses sous catégories
        $parent = new CategorieP();
        $parent->setNom('Alimentaire');
        $parent->setDescription('Tous les aliments');
        $parent->setSlug('alimentaire');
        $parent->setImg('alimentaire');
        $manager->persist($parent);

        $categoryP = new CategorieP();
        $categoryP->setNom('Boisson');
        $categoryP->setParent($parent);
        $categoryP->setDescription('Toutes les boissons');
        $categoryP->setSlug('boissons');
        $categoryP->setImg('boisson');
        $manager->persist($categoryP);

        $categoryP = new CategorieP();
        $categoryP->setNom('Nouilles');
        $categoryP->setParent($parent);
        $categoryP->setDescription('Toutes les nouilles');
        $categoryP->setSlug('nouilles');
        $categoryP->setImg('nouilles');
        $manager->persist($categoryP);

        $categoryP = new CategorieP();
        $categoryP->setNom('Mocchi');
        $categoryP->setParent($parent);
        $categoryP->setDescription('Tous nos mocchis');
        $categoryP->setSlug('mocchi');
        $categoryP->setImg('mocchi');
        $manager->persist($categoryP);

        $manager->flush();
    }
}
