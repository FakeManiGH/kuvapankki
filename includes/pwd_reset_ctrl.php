<?php

// Puhdistaa käyttäjän syötteen
function trim_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Tarkistaa onko käyttäjän syötteet tyhjiä
function is_input_empty($userinfo) {
    if (empty($userinfo)) {
        return true;
    } else {
        return false;
    }
}

// Tarkistaa onko käyttäjän syöte oikeassa muodossa
function is_username_or_email_correct($userinfo) {
    if (!filter_var($userinfo, FILTER_VALIDATE_EMAIL) || !preg_match("/^[a-zA-Z0-9]*$/", $userinfo)) {
        return false;
    } else {
        return true;
    }
}
