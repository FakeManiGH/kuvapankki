<?php

declare(strict_types=1);

// Puhdistaa käyttäjän syötteen
function trim_input($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

// Tarkistaa onko käyttäjän syötteet tyhjiä ja vertaa niitä tietokannan tietoihin
function compare_info($input, $data) {
    if (empty($input) || $input === $data) {
        return $data;
    } else {
        return $input;
    }
}