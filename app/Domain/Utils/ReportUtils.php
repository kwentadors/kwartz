<?php

namespace App\Domain\Utils;

class ReportUtils
{
    public static function array_group(array $array, callable $getGroupFn)
    {
        return array_reduce($array, function ($result, $entry) use ($getGroupFn) {
            $group = (string)$getGroupFn($entry);
            if (!array_key_exists($group, $result)) {
                $result[$group] = [];
            }
            $result[$group][] = $entry;

            return $result;
        }, []);
    }
}
