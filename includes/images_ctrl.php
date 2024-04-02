<?php

// Function to sanitize input
function trim_input($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
}

// function to check filesize is under 5MB
function check_file_size($file_size) {
    if ($file_size > 5000000) {
        return false;
    } else {
        return true;
    }
}

// function to check file type is jpg, jpeg or png
function check_file_type($fileExt) {
    $allowed = array('jpg', 'jpeg', 'png');
    if (in_array($fileExt, $allowed)) {
        return true;
    } else {
        return false;
    }
}
