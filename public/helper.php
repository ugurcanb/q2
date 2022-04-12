<?php

if (!function_exists('prepareString')) {
    /**
     * @param string $string
     * @return string
     */
    function prepareString(string $string): string
    {
        if (php_sapi_name() === 'cli') {
            return $string;
        }
        return str_replace(["\n", "\t"], ["<br>", str_repeat("&nbsp;", 4)], $string);
    }
}
