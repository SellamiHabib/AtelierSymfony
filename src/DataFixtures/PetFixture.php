<?php

namespace App\DataFixtures;

use App\Entity\Pet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PetFixture extends Fixture
{
    public function load(ObjectManager $manager): void {
        $faker = Factory::create();
        for ($i = 1; $i < 300; $i++) {
            $pet = new Pet();
            $pet->setRace($faker->title);
            $pet->setName($faker->firstName);
            $pet->setAgeAge($faker->randomNumber(2));

            $manager->persist($pet);
        }


        $manager->flush();
    }
}
