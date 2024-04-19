<?php

declare(strict_types=1);

// Function to sanitize post data
function sanitize_array($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = sanitize_array($value);
        }
    } else if (is_string($data)) {
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    } else if (is_integer($data)) {
        $data = (int)$data;
    } else if (is_float($data)) {
        $data = (float)$data;
    }
    return $data;
}