<?php

namespace App\Domain\Utils;

class DateUtils {

    public static function monthNameAbbr(int $index) {
        return strtoupper(date('M', mktime(0, 0, 0, $index, 1)));
    }
}