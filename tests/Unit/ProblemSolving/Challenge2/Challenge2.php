<?php

namespace Tests\Unit\ProblemSolving\Challenge2;

class Challenge2
{
    public static function diceFacesCalculator($dice1, $dice2, $dice3)
    {
        $length = 3;
        $dices = [];

        for ($i = 1; $i <= $length; $i++) {
            $current = "dice$i";
            if (${$current} >= 1 && ${$current} <= 6) {
                if (isset($dices[$current])) {
                    $dices[${$current}][] = $dices[${$current}];
                } else {
                    $dices[${$current}][] = ${$current};
                }
            } else {
                throw new \Exception('Dice out of number range');
            }
        }

        asort($dices);

        if (count($dices) == $length) {
            return end($dices)[0];
        } else {
            $values = [
                'value' => 0,
                'count' => 0,
            ];

            foreach ($dices as $k => $v) {
                if (count($v) > $values['count']) {
                    $values['value'] = $k;
                    $values['count'] = count($v);
                }
            }
        }

        return $values['value'] * $values['count'];
    }
}
