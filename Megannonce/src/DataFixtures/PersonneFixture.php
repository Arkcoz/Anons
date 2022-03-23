<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Personne;

class PersonneFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 1; $i++){
            $personne = new Personne();
            $personne   ->setNom("Moniot")
                        ->setPrenom("Lucas")
                        ->setPassword("test")
                        ->setPhone("0782993771")
                        ->setEmail("lucas.mnt21@gmail.com")
                        ->setIsAdmin(true)
                        ->setCreatedAt(new \DateTime());
            $manager->persist($personne);
        }

        $manager->flush();
    }
}
