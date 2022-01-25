<?php

namespace App\Service;

class HandleUserStats
{
    public function getUserData(array $stats): array
    {
        $turns = 0;
        $totalAccuracy = 0;
        $categories = [false, false, false];

        foreach ($stats as $key => $precisionCategory) {
            $turns += $precisionCategory['turns'];
            $totalAccuracy += $precisionCategory['sumAccuracy'];
            $categories[$precisionCategory['accuracyCategory']] = $key + 1;
        }

        return $this->generateDataArray($turns, $totalAccuracy, $categories, $stats);
    }

    private function generateDataArray(int $turns, int $totalAccuracy, array $categories, array $stats): array
    {
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
                "turns" => $categories[0] ? $stats[$categories[0] - 1]['turns'] : 0,
                "width" => $categories[0] ?  $stats[$categories[0] - 1]['turns'] / $turns * 100 : 0,
            ],
            "precision1" => [
                "turns" => $categories[1] ? $stats[$categories[1] - 1]['turns'] : 0,
                "width" => $categories[1] ?  $stats[$categories[1] - 1]['turns'] / $turns * 100 : 0,
            ],
            "precision2" => [
                "turns" => $categories[2] ? $stats[$categories[2] - 1]['turns'] : 0,
                "width" => $categories[2] ?  $stats[$categories[2] - 1]['turns'] / $turns * 100 : 0,
            ],
        ];

        return $userData;
    }
}
