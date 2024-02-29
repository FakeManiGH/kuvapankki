<?php

function trim_input($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

// tarkistetaan täsmäävätkö tokenit
function tokens_match($token1, $token2) {
    if ($token1 === $token2) {
        return true;
    } else {
        return false;
    }
}

// Tarkistetaan ovatko syötteet tyhjiä
function inputs_empty($input1, $input2) {
    if (empty($input1) || empty($input2)) {
        return true;
    } else {
        return false;
    }
}

// Tarkistetaan täsmäävätkö salasanat
function passwords_match($pwd1, $pwd2) {
    if ($pwd1 === $pwd2) {
        return true;
    } else {
        return false;
    }
}