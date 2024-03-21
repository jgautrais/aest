<?php

namespace App\DataFixtures;

use App\Entity\Turn;
use App\DataFixtures\UserFixtures;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TurnFixtures extends Fixture implements DependentFixtureInterface
{
    private const TURNS = 10000;
    private const ACCURACY_HIGH = 5;
    private const ACCURACY_MIDDLE = 10;

    public function load(ObjectManager $manager): void
    {
        $min = strtotime("21-12-11");
        $max = strtotime("22-01-27");

        for ($i = 0; $i < self::TURNS; $i++) {
            $turn = new Turn();

            $userId = rand(0, 24);
            $turn->setUser($this->getReference("user_$userId"));

            $area = rand(20, 95);
            $estimate = $area - rand(0, 15);

            $turn->setArea($area);
            $turn->setEstimate($estimate);

            $accuracy = abs($area - $estimate);
            $accuracyCategory = null;
            if ($accuracy < self::ACCURACY_HIGH) {
                $accuracyCategory = 0;
            } elseif ($accuracy < self::ACCURACY_MIDDLE) {
                $accuracyCategory = 1;
            } else {
                $accuracyCategory = 2;
            }

            $turn->setAccuracy($accuracy);
            $turn->setAccuracyCategory($accuracyCategory);

            $date = rand($min, $max);
            $dateString = date("Y-m-d", $date);
            $dateTime = new DateTime($dateString);

            $turn->setDate($dateTime);

            $manager->persist($turn);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
