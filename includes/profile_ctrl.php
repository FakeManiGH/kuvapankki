<?php

declare(strict_types=1);

// Function to trim input
function trim_input($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

// Function to compare input and data
function compare_info($input, $data) {
    if (empty($input) || $input === $data) {
        return $data;
    } else {
        return $input;
    }
}

// Function to secure viewable phone number
function securePhone($phone) {
    $start = substr($phone, 0, -4);
    $start = str_repeat("*", strlen($start));
    $end = substr($phone, -4);
    $securePhone = $start . $end;
    return $securePhone;
}

// Function to secure viewable email
function secureEmail($email) {
    $start = substr($email, 0, 3);
    $middle = substr($email, 3, strpos($email, '@') - 3);
    $end = substr($email, strpos($email, '@'));
    $secureEmail = $start . str_repeat("*", strlen($middle)) . $end;
    return $secureEmail;
}