<?php

namespace App\DataFixtures;

use App\Entity\Abonnement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AbonnementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->createAbonnement('Abonnement découverte',19.99,'Abonnement pour découvrir nos service', $manager);
        $this->createAbonnement('Abonnement habitué',50,'Abonnement pour nos habitués qui souhaite payer au mois', $manager);
        $this->createAbonnement('Abonnement professionnel',850.85,'Abonnement pour nos professionnel à l année', $manager);

        $manager->flush();
    }

    public function createAbonnement(string $nom, float $prix, string $description, ObjectManager $manager){
        
        $abonnement = new Abonnement();
        $abonnement->setNom($nom);
        $abonnement->setPrix($prix);
        $abonnement->setDescription($description);
        $manager->persist($abonnement);

        return $abonnement;
    }
}
