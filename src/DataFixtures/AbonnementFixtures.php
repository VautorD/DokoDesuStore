<?php

namespace App\DataFixtures;

use App\Entity\Abonnement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AbonnementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $abonnement = new Abonnement();
        $abonnement->setNom('Abonnement découverte');
        $abonnement->setPrix('19,99');
        $abonnement->setDescription('Abonnement pour découvrir nos service');
        $manager->persist($abonnement);

        $abonnement = new Abonnement();
        $abonnement->setNom('Abonnement habitué');
        $abonnement->setPrix('50');
        $abonnement->setDescription('Abonnement pour nos habitués qui souhaite payer au mois');
        $manager->persist($abonnement);

        $abonnement = new Abonnement();
        $abonnement->setNom('Abonnement professionnel');
        $abonnement->setPrix('850,85');
        $abonnement->setDescription('Abonnement pour nos professionnel à l année');
        $manager->persist($abonnement);

        $manager->flush();
    }
}
