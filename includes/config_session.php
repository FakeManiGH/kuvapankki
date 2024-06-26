<?php

// Asetetaan sessionin asetukset, jos sessiota ei ole vielä aloitettu (LISÄÄ tämä tiedostoon, jos se on ennen headeria)
if (session_status() == PHP_SESSION_NONE) {
    ini_set('session.use_only_cookies', 1);
    ini_set('session.use_strict_mode', 1);

    session_set_cookie_params([
        'lifetime' => 1800,
        'path' => '/',
        'domain' => 'localhost',
        'secure' => true,
        'httponly' => true,
    ]);
}

// Aloitetaan sessio jos sitä ei ole vielä aloitettu
checkSession();


// Uusitaan session ID 30 minutin välein
if (is_logged_in()) {
    if (!isset($_SESSION['last_regeneration'])) {
        regenerate_session_id_loggedin();
    } else {
        $interval = 60 * 30;
        if (time() - $_SESSION['last_regeneration'] > $interval) {
            regenerate_session_id_loggedin();
            checkSession();
        }
    }
} else {
    // Jos käyttäjä ei ole kirjautunut sisään
    if (!isset($_SESSION['last_regeneration'])) {
        regenerate_session_id();
    } else {
        $interval = 60 * 30;
        if (time() - $_SESSION['last_regeneration'] > $interval) {
            regenerate_session_id();
            checkSession();
        }
    }
}


// funktio uusimaan session ID kun käyttäjä on kirjautunut sisään
function regenerate_session_id_loggedin() {
    session_regenerate_id(true);

    $user_id = $_SESSION['user_id'];
    $newSessionId = session_create_id();
    $sessionId = $newSessionId . "_" . $user_id;
    session_id($sessionId);

    $_SESSION['last_regeneration'] = time();
}

// funktio uusimaan session ID kun käyttäjä ei ole kirjautunut sisään
function regenerate_session_id() {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

// funktio tarkistamaan session tila
function checkSession() {
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

// Funktio tarkistamaan onko käyttäjä kirjautunut sisään
function is_logged_in() {
    if (isset($_SESSION['user_id'])) {
        return true;
    } else {
        return false;
    }
}