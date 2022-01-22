<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $usersData = [
            ["jeremy@email.com", "jeremy", "Jerem"],
            ["marine@email.com", "marine", "Marine"]
        ];

        foreach ($usersData as $userData) {
            $user = new User();

            $user->setEmail($userData[0]);

            $hashedPassword = $this->passwordHasher->hashPassword($user, $userData[1]);
            $user->setPassword($hashedPassword);

            $user->setPseudo($userData[2]);
            $user->setGameCount(0);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
