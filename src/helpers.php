<?php

if (!function_exists('afterLast')) {
    function afterLast($subject, $search)
    {
        if ($search === '') {
            return $subject;
        }

        $position = mb_strrpos($subject, (string)$search);
        
        if ($position === false) {
            return $subject;
        }

        return mb_substr($subject, $position + strlen($search), null, 'UTF-8');
    }
}

if (!function_exists('beforeLast')) {
    function beforeLast($subject, $search)
    {
        if ($search === '') {
            return $subject;
        }

        $pos = mb_strrpos($subject, $search);

        if ($pos === false) {
            return $subject;
        }

        return mb_substr($subject, 0, $pos, 'UTF-8');
    }
}

if (!function_exists('wrap')) {
    function wrapArray($value): array
    {
        if (is_null($value)) {
            return [];
        }

        return is_array($value) ? $value : [$value];
    }
}

if (!function_exists('blank')) {
    /**
     * Determine if the given value is "blank".
     *
     * @param mixed $value
     * @return bool
     */
    function blank(mixed $value): bool
    {
        if (is_null($value)) {
            return true;
        }

        if (is_string($value)) {
            return trim($value) === '';
        }

        if (is_numeric($value) || is_bool($value)) {
            return false;
        }

        if ($value instanceof Countable) {
            return count($value) === 0;
        }

        return empty($value);
    }
}

if (!function_exists('filled')) {
    /**
     * Determine if a value is "filled".
     *
     * @param mixed $value
     * @return bool
     */
    function filled(mixed $value): bool
    {
        return !blank($value);
    }
}