<?php

// Puhdistaa käyttäjän syötteen
function trim_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Tarkistaa onko käyttäjän syötteet tyhjiä
function is_input_empty($username, $pwd) {
    if (empty($username) || empty($pwd)) {
        return true;
    } else {
        return false;
    }
}

// Tarkistaa onko käyttäjätunnus oikea
function is_username_correct(string $username, string $db_username) {
    if ($username === $db_username) {
        return true;
    } else {
        return false;
    }
}

// Tarkistaa onko käyttäjä vahvistettu
function is_user_verified(int $verified) {
    if ($verified === 1) {
        return true;
    } else {
        return false;
    }
}

