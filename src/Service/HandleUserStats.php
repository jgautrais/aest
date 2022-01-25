<?php

namespace App\Service;

class HandleUserStats
{
    public function getUserData(array $stats): array
    {
        $turns = 0;
        $totalAccuracy = 0;

        foreach ($stats as $precisionCategory) {
            $turns += $precisionCategory['turns'];
            $totalAccuracy += $precisionCategory['sumAccuracy'];
        }

        if (0 === $turns || 0 === $totalAccuracy) {
            $userData = [
                "turns" => "No data",
                "meanAccuracy" => "--",
                "precision0" => [
                    "turns" => "--",
                    "width" => 0,
                ],
                "precision1" => [
                    "turns" => "--",
                    "width" => 0,
                ],
                "precision2" => [
                    "turns" => "--",
                    "width" => 0,
                ],
            ];

            return $userData;
        }

        $userData = [
            "turns" => $turns,
            "meanAccuracy" => round($totalAccuracy / $turns, 1),
            "precision0" => [
                "turns" => $stats[0]['turns'],
                "width" => $stats[0]['turns'] / $turns * 100,
            ],
            "precision1" => [
                "turns" => $stats[1]['turns'],
                "width" => $stats[1]['turns'] / $turns * 100,
            ],
            "precision2" => [
                "turns" => $stats[2]['turns'],
                "width" => $stats[2]['turns'] / $turns * 100,
            ],
        ];

        return $userData;
    }
}
