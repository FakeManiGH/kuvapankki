<?php

declare(strict_types=1);

// Puhdistaa näytettävät tiedot
function trim_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Syötteen minimi ja maksimipituus
function input_length($input, $min, $max) {
    if (strlen($input) < $min || strlen($input) > $max) {
        return false;
    }
    return true;
}

function process_friend_data($friend) {
    return [
        'username' => $friend['username'],
        'user_id' => $friend['user_id'],
        'user_img' => $friend['user_img']
    ];
}