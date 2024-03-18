<?php 

declare(strict_types=1);

// Function to sanitize input
function trim_input($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

// Function to check if input is in range
function isInRange($input, int $min, int $max) {
    if ($input >= $min && $input <= $max) {
        return true;
    } else {
        return false;
    }
}