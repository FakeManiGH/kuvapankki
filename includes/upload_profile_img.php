<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include "config_session.php";

    $user_id = $_SESSION['user_id'];
    $image = $_FILES['userfile'];

    // Check if there was an error with the file upload
    if ($image['error'] !== UPLOAD_ERR_OK) {
        die("File upload error: " . $image['error']);
    }

    // Rest of your code...
    $image_name = $_FILES['userfile']['name'];
    $image_tmp_name = $_FILES['userfile']['tmp_name'];
    $image_size = $_FILES['userfile']['size'];
    $image_error = $_FILES['userfile']['error'];
    $image_type = $_FILES['userfile']['type'];

    $image_ext = explode('.', $image_name);
    $image_actual_ext = strtolower(end($image_ext));

    $allowed = ['jpg', 'jpeg', 'png'];
    
    // Tarkistetaan onko tiedostotyyppi sallittu
    if (in_array($image_actual_ext, $allowed)) {

        // Tarkistetaan onko tiedosto latautunut onnistuneesti
        if ($image_error === 0) {

            // Tarkistetaan onko tiedosto liian suuri
            if ($image_size < 2000000) {
                $image_name_new = $user_id.".".$image_actual_ext;
                $image_destination = '../profile_images/'.$image_name_new;
                $image_destination_db = 'profile_images/'.$image_name_new;
                move_uploaded_file($image_tmp_name, $image_destination);

                try {
                    include "connections.php";
                    include "profile_update_model.php";

                    // Päivitetään tietokantaan uusi kuva
                    update_profile_img($pdo, $user_id, $image_destination_db);

                    // Suljetaan yhteys tietokantaan ja poistetaan muuttujat
                    $pdo = null;
                    $stmt = null;

                    // Päivitetään sessioon uusi kuva
                    $_SESSION['user_img'] = "profile_images/".$image_name_new;

                    // Asetetaan onnistumisviesti ja ohjataan takaisin profiiliin.
                    $_SESSION['profile_update_success'] = "Profiilikuva päivitetty onnistuneesti!";

                    header("Location: ../profiili.php?kuva=päivitetty");
                    exit();


                } catch (PDOException $e) {
                    $_SESSION['image_update_err'] = "Tietokantaongelma! Ole hyvä ja yritä uudelleen!";
                    header("Location: ../profiili.php?virhe=tietokanta");
                    exit();
                }
            } else {
                $_SESSION['image_update_err'] = "Tiedosto on liian suuri! Max 2MB!";
                header("Location: ../profiili.php?virhe=liian_suuri");
                exit();
            }
        } else {
            $_SESSION['image_update_err'] = "Tiedostoa ei voitu ladata! Ole hyvä ja yritä uudelleen!";
            header("Location: ../profiili.php?virhe=lataus");
            exit();
        }
    } else {
        $_SESSION['image_update_err'] = "Tiedostotyyppi ei ole sallittu! Vain .jpg, .jpeg tai .png sallittu!";
        header("Location: ../profiili.php?virhe=tyyppi");
        exit();
    }
} else {

    header("Location: ../index.php?pääsy=kielletty");
    exit();
}