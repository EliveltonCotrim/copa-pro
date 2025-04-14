<?php

if (!function_exists('clear_string')) {
    function clear_string(?string $string): string
    {
        if (is_null($string)) {
            return null;
        }

        return (string) preg_replace('/[^A-Za-z0-9]/', '', $string);
    }
}
