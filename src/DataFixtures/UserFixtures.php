<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class UsersFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwdEncoder){

    }
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setNom('Dupont');
        $admin->setPrenom('Dupont');
        $admin->setEmail('d@gmail.com');
        $admin->setTel('0102030405');
        $admin->setAbonnement(null);
        $admin->setPassword(
            $this->passwdEncoder->hashPassword($admin, '123456')
        );
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setVerified(true);
        $manager->persist($admin);

        $manager->flush();
    }
}