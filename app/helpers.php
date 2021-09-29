<?php

if (!function_exists('confirmation_code')) {
    function confirmation_code($digits = 4)
    {
        $seed = str_split('0123456789'); // and any other characters
        shuffle($seed);
        $rand = '';
        foreach (array_rand($seed, $digits) as $k) $rand .= $seed[$k];
        return $rand;
    }
}
