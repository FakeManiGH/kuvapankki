<?php
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_set_cookie_params([
    'lifetime' => 1801,
    'path' => '/',
    'domain' => 'localhost',
    'secure' => true,
    'httponly' => true,
]);

session_start();

if (isset($_GET['g']) && !empty($_GET['g'])) {
    $user_id = $_SESSION['user_id'];
    $gallery_id = htmlspecialchars($_GET['g']);

    try {
        include 'includes/connections.php';
        require 'includes/gallery_model.php';
        require 'includes/gallery_ctrl.php';
        require_once 'includes/friends_model.php';

        // Haetaan gallerian tiedot
        $gallery = get_gallery_details($pdo, $gallery_id);
        $gallery_members = get_gallery_members($pdo, $gallery_id);

        // Tarkistetaan onko galleria olemassa
        if (empty($gallery)) {
            $_SESSION['404_error'] = 'Valitsemaasi galleriaa ei löytynyt.';
            header('Location: 404.php?sivua=ei_loydy');
            die();
        }

        // Tarkistetaan onko käyttäjällä oikeus nähdä galleria
        // 1 = Yksityinen, 2 = Kaverit, 3 = Julkinen
        if ($gallery['visibility'] == '1') {
            if ($gallery['owner_id'] != $user_id) {
                $_SESSION['404_error'] = 'Valitsemaasi galleriaa ei löytynyt tai sinulla ei ole oikeutta siihen.';
                header('Location: 404.php?sivu=ei_loydy');
                die();
            }
        } else if ($gallery['visibility'] == '2') {
            if (!is_friend($pdo, $user_id, $gallery['owner_id']) && $gallery['owner_id'] != $user_id) {
                $_SESSION['404_error'] = 'Valitsemaasi galleriaa ei löytynyt tai sinulla ei ole oikeutta siihen.';
                header('Location: 404.php?sivu=ei_loydy');
                die();
            }
        }

        // Asetetaan gallerian muuttujat
        $title = htmlspecialchars($gallery['name']);
        $description = htmlspecialchars($gallery['description']);
        $cover_img = htmlspecialchars($gallery['cover_img']);
        $visibility = htmlspecialchars($gallery['visibility']);
        $created = htmlspecialchars(date('d.m.Y', strtotime($gallery['created'])));
        $updated = htmlspecialchars(date('d.m.Y', strtotime($gallery['updated'])));
        $owner = htmlspecialchars($gallery['owner_id']);
        $owner_username = htmlspecialchars($gallery['owner_username']);
        $member_count = htmlspecialchars(get_gallery_member_count($pdo, $gallery_id));
        $user_role = htmlspecialchars(check_user_role($gallery_members, $user_id));
        $owner = htmlspecialchars($gallery['owner_id']);

        // Haetaan kuvat
        $gallery_images = get_gallery_images($pdo, $gallery_id);
        $image_count = count($gallery_images);

        // Suljetaan tietokantayhteys
        $pdo = null;
        

    } catch (PDOException $e) {
        error_log("PDOException: " . $e->getMessage());
        die("Tietokantavirhe. Yritä myöhemmin uudelleen.");
    }

} else {

    $_SESSION['404_error'] = 'Valitsemaasi galleriaa ei löytynyt tai sinulla ei ole siihen oikeutta.';
    header('Location: 404.php?sivu=ei_loydy'); 
    die();
}