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
function is_in_range($input, int $min, int $max) {
    if ($input >= $min && $input <= $max) {
        return true;
    } else {
        return false;
    }
}

// Function to check tag count
function validateTags($tags) {
    $tagArray = preg_split('/\s+/', $tags);
    if (count($tagArray) < 1 || count($tagArray) > 15) {
        return false;
    }
    return true;
}

// Function to check user role
function check_user_role(array $gallery_members, int $user_id) {
    foreach ($gallery_members as $member) {
        if ($member['user_id'] === $user_id) {
            return $member['role_id'];
        }
    }
}