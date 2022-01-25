<?php

namespace App\Controller;

use DateTime;
use Exception;
use App\Entity\Turn;
use App\Entity\User;
use App\Service\HandleUserStats;
use App\Repository\TurnRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TurnController extends AbstractController
{
    /**
     * @Route("/isLogged", name="isLogged")
     */
    public function isLogged(): Response
    {
        $user = $this->getUser();

        if (null === $user) {
            return $this->json(
                ['isLogged' => false]
            );
        }
        return $this->json(
            ['isLogged' => true]
        );
    }

    /**
     * @Route("/saveTurn/{area}/{estimate}/{accuracy}/{accuracyCategory}/", name="saveTurn")
     */
    public function saveTurn(
        int $area,
        int $estimate,
        int $accuracy,
        int $accuracyCategory,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();

        if (null === $user) {
            return $this->json(
                ['success' => false]
            );
        }

        if (!$user instanceof User) {
            throw new Exception('User not authenticated');
        }

        $turn = new Turn();

        $turn->setUser($user);
        $turn->setArea($area);
        $turn->setEstimate($estimate);
        $turn->setAccuracy($accuracy);
        $turn->setAccuracyCategory($accuracyCategory);
        $turn->setDate(new DateTime());

        $entityManager->persist($turn);
        $entityManager->flush();

        return $this->json(
            ['success' => true]
        );
    }

    /**
     * @Route("/incrementGameCount/", name="incrementGameCount")
     */
    public function incrementGameCount(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (null === $user) {
            return $this->json(
                ['success' => false]
            );
        }

        if (!$user instanceof User) {
            throw new Exception('User not authenticated');
        }

        $user->setGameCount($user->getGameCount() + 1);
        $entityManager->flush();

        return $this->json(
            ['success' => true]
        );
    }


    /**
     * @Route("/getUserStats/{period}", name="getUserStats")
     */
    public function getUserStats(
        string $period,
        TurnRepository $turnRepository,
        HandleUserStats $handleUserStats
    ): Response {
        $user = $this->getUser();

        if (null === $user) {
            return $this->json(
                ['success' => false]
            );
        }

        if (!$user instanceof User) {
            throw new Exception('User not authenticated');
        }

        $userStats = null;

        if ($period === "all") {
            $userStats = $turnRepository->getStatsAllTime($user);
        } elseif ($period === "month") {
            $userStats = $turnRepository->getStatsLastMonth($user);
        } elseif ($period === "week") {
            $userStats = $turnRepository->getStatsLastWeek($user);
        } else {
            $userStats = $turnRepository->getStatsToday($user);
        }

        $userData = $handleUserStats->getUserData($userStats);

        return $this->json(
            ['stats' => $userData]
        );
    }
}
