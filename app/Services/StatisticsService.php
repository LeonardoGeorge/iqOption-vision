<?php

namespace App\Services;

class StatisticsService
{
    /**
     * Calcula o desvio padrão de um array de números
     */
    public static function standardDeviation($numbers)
    {
        $n = count($numbers);
        if ($n === 0) {
            return 0;
        }

        $mean = array_sum($numbers) / $n;
        $variance = 0.0;

        foreach ($numbers as $number) {
            $variance += pow($number - $mean, 2);
        }

        return sqrt($variance / $n);
    }

    /**
     * Calcula a média de um array
     */
    public static function mean($numbers)
    {
        if (empty($numbers)) {
            return 0;
        }
        return array_sum($numbers) / count($numbers);
    }

    /**
     * Calcula a correlação entre dois arrays
     */
    public static function correlation($array1, $array2)
    {
        if (count($array1) !== count($array2)) {
            return 0;
        }

        $n = count($array1);
        $mean1 = self::mean($array1);
        $mean2 = self::mean($array2);

        $numerator = 0;
        $denom1 = 0;
        $denom2 = 0;

        for ($i = 0; $i < $n; $i++) {
            $numerator += ($array1[$i] - $mean1) * ($array2[$i] - $mean2);
            $denom1 += pow($array1[$i] - $mean1, 2);
            $denom2 += pow($array2[$i] - $mean2, 2);
        }

        if ($denom1 == 0 || $denom2 == 0) {
            return 0;
        }

        return $numerator / sqrt($denom1 * $denom2);
    }

    /**
     * Calcula o Beta (risco sistemático) entre um ativo e o mercado
     */
    public static function beta($assetReturns, $marketReturns)
    {
        $covariance = self::covariance($assetReturns, $marketReturns);
        $marketVariance = self::variance($marketReturns);

        if ($marketVariance == 0) {
            return 0;
        }

        return $covariance / $marketVariance;
    }

    /**
     * Calcula a covariância entre dois arrays
     */
    public static function covariance($array1, $array2)
    {
        if (count($array1) !== count($array2)) {
            return 0;
        }

        $n = count($array1);
        $mean1 = self::mean($array1);
        $mean2 = self::mean($array2);

        $covariance = 0;
        for ($i = 0; $i < $n; $i++) {
            $covariance += ($array1[$i] - $mean1) * ($array2[$i] - $mean2);
        }

        return $covariance / $n;
    }

    /**
     * Calcula a variância de um array
     */
    public static function variance($numbers)
    {
        $mean = self::mean($numbers);
        $variance = 0.0;

        foreach ($numbers as $number) {
            $variance += pow($number - $mean, 2);
        }

        return $variance / count($numbers);
    }

    /**
     * Calcula o valor máximo em um array
     */
    public static function max($numbers)
    {
        return count($numbers) ? max($numbers) : 0;
    }

    /**
     * Calcula o valor mínimo em um array
     */
    public static function min($numbers)
    {
        return count($numbers) ? min($numbers) : 0;
    }

    /**
     * Calcula a mediana de um array
     */
    public static function median($numbers)
    {
        if (empty($numbers)) {
            return 0;
        }

        sort($numbers);
        $count = count($numbers);
        $middle = floor($count / 2);

        if ($count % 2 == 0) {
            return ($numbers[$middle - 1] + $numbers[$middle]) / 2;
        } else {
            return $numbers[$middle];
        }
    }
}
