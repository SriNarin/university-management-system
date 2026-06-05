<?php

namespace App\Helpers;

class GradeCalculator
{
    /**
     * Maps percentage scores directly to standard Cambodian grading letter categories.
     */
    public static function getLetter(float $score, float $maxScore): string
    {
        if ($maxScore < 50) return 'F';
        
        $percentage = ($score / $maxScore) * 100;

        return match (true) {
            $percentage >= 100 => 'A+',
            $percentage >= 95 => 'A',
            $percentage >= 90 => 'B+',
            $percentage >= 85 => 'B',
            $percentage >= 80 => 'C+',
            $percentage >= 75 => 'C',
            $percentage >= 70 => 'D+',
            $percentage >= 65 => 'D',
            $percentage >= 60 => 'E+',
            $percentage >= 50 => 'E',
            default           => 'F',
        };
    }
}