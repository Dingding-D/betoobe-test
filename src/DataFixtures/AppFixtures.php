<?php

namespace App\DataFixtures;

use App\Factory\ActivityFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        ActivityFactory::createMany(5);
    }
}
