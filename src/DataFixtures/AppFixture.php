<?php

// src/DataFixtures/AppFixtures.php
namespace App\DataFixtures;

use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 20000; $i++) {
            $q = new Question();
            $q->setText('question ' . strtoupper(bin2hex(random_bytes(20)) . ' ?'));
            $manager->persist($q);
        }

        $manager->flush();
    }

}