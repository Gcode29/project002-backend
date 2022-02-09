<?php

if (! function_exists('trim_whitespaces')) {
    /**
     * Trim whitespaces from a string.
     *
     * @param  string  $value
     * @return string
     */
    function trim_whitespaces(string $value): string
    {
        return trim(preg_replace(['/\s{2,}/', '/[\t\n]/'], ' ', $value));
    }
}