<?php

namespace App\DataFixtures;
 
use App\Factory\CategoryFactory;
use App\Factory\CocktailFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        UserFactory::new()->createMany(20);

        CategoryFactory::new()->createMany(10);
        CocktailFactory::new()->createMany(15);

        $manager->flush();
    }
}
