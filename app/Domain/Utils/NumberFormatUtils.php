<?php

namespace App\Domain\Utils;

class NumberFormatUtils {

    public static function formatNumber($number, int $decimalPlaces=2) {
        return number_format($number, $decimalPlaces);
    }
}