<?php

namespace Tests\Unit\ProblemSolving\Challenge1;

class Challenge1
{
    public static function numberFractionsCalculator($numbers)
    {
        $subtotals = [
            'positives' => [],
            'negative' => [],
            'zeros' => [],
        ];

        foreach ($numbers as $num) {
            if ($num == 0) {
                $subtotals['zeros'][] = $num;
            } elseif ($num > 0) {
                $subtotals['positives'][] = $num;
            } else {
                $subtotals['negative'][] = $num;
            }
        }

        $response = [
            'positives' => self::calcPercentage(count($numbers), count($subtotals['positives'])),
            'negative' => self::calcPercentage(count($numbers), count($subtotals['negative'])),
            'zeros' => self::calcPercentage(count($numbers), count($subtotals['zeros'])),
        ];

        //total numbers (6) => 100 %
        // total positives (3) => X%
        // X% = total positives (3) X 100 % / total numbers (6)

        return $response;
    }

    private static function calcPercentage($total, $subTotal)
    {
        return round(($subTotal * 100 / $total) / 100, 6);
    }
}
