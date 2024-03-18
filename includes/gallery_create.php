<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'config_session.php';

    $name = $_POST['name'];
    $description = $_POST['description'];
    $visibility = $_POST['visibility'];
    $cover_img = $_FILES['cover_img'];
    $tags = $_POST['tags'];
    $owner_id = $_SESSION['user_id'];

    try {
        include 'connections.php';
        require 'regex.php';
        require 'gallery_ctrl.php';
        require 'gallery_model.php';

        // Tarkistetaan onko vaaditut kentät täytetty
        if (empty($name) || empty($description)) {
            $_SESSION['gallery_create_err'] = 'Nimi ja kuvaus ovat pakollisia kenttiä.';
            header('Location: ../lisaa_galleria.php?virhe=tyhjäkenttä');
            exit();
        }

        // Puhdistetaan syötteet
        $name = trim_input($name);
        $description = trim_input($description);
        $tags = trim_input($tags);

        // Tarkistetaan onko nimi oikeanlainen
        if (!preg_match($patternGallery, $name)) {
            $_SESSION['gallery_create_err'] = 'Virheellinen nimi.';
            header('Location: ../lisaa_galleria.php?virhe=nimi');
            exit();
        }

        // Tarkistetaan onko kuvaus oikeanlainen
        if (!preg_match($patternDesc, $description)) {
            $_SESSION['gallery_create_err'] = 'Virheellinen kuvaus.';
            header('Location: ../lisaa_galleria.php?virhe=kuvaus');
            exit();
        }

        // Luodaan gallerialle ID
        $gallery_id = create_gallery_id($pdo);

        // Tarkistetaan näkyvyys
        if (!is_numeric($visibility)) {
            $_SESSION['gallery_create_err'] = 'Virheellinen näkyvyys.';
            header('Location: ../lisaa_galleria.php?virhe=näkyvyys_tyyppi');
            exit();
        }

        if (!isInRange($visibility, 1, 3)) {
            $_SESSION['gallery_create_err'] = 'Virheellinen näkyvyys.';
            header('Location: ../lisaa_galleria.php?virhe=näkyvyys_numero');
            exit();
        }



        // POINT OF NO RETURN

        // Luodaan galleria
        create_gallery($pdo, $gallery_id, $owner_id, $name, $description, $visibility, $tags);

        // luodaan käyttäjäryhmä admin käyttäjällä (rooli 1 = omistaja, 2 = jäsen, 3 = katselija)
        create_gallery_user($pdo, $gallery_id, $owner_id, 1);

        // Tarkistetaan näkyvyys ja lisätään käyttäjät ryhmään tarvittaessa.
        if ($visibility == 2 || $visibility == 3) {
            $selected_users = $_POST['selected_users']; 
            $selected_users = array_map('trim_input', $selected_users);
            $selected_users = array_filter($selected_users);
            $selected_users = array_unique($selected_users);

            foreach ($selected_users as $user) {
                create_gallery_user($pdo, $gallery_id, $user, 2);
            }
        }

        // Luodaan gallerian id:n mukainen kansio
        $gallery_dir = '../../galleries/'.$gallery_id;
        mkdir($gallery_dir, 0777, true);

        // Tarkistetaan onko profiilikuva ladattu
        if (!empty($cover_img)) {
            
            $imageError = $cover_img['error'];

            // Tarkistetaan onko virheitä tiedoston latauksessa
            if ($imageError == UPLOAD_ERR_OK) {
                $cover_img_name = $cover_img['name'];
                $cover_img_tmp_name = $cover_img['tmp_name'];
                $cover_img_size = $cover_img['size'];
                $cover_img_error = $cover_img['error'];
                $cover_img_ext = explode('.', $cover_img_name);
                $cover_img_actual_ext = strtolower(end($cover_img_ext));
                $cover_img_allowed = ['jpg', 'jpeg', 'png'];

                // Tarkistetaan onko tiedostotyyppi sallittu
                if (in_array($cover_img_actual_ext, $cover_img_allowed)) {

                    // Tarkistetaan onko tiedosto liian suuri
                    if ($cover_img_size < 5000000) {
                        $cover_img_name_new = $gallery_id.".".$cover_img_actual_ext;
                        $cover_img_destination = $gallery_dir.'/'.$cover_img_name_new;
                        move_uploaded_file($cover_img_tmp_name, $cover_img_destination);

                        // Päivitetään tietokantaan uusi kuva
                        update_gallery_cover($pdo, $gallery_id, $cover_img_destination);

                        // Asetetaan galleria luoduksi
                        $_SESSION['gallery_create_success'] = 'Galleria luotu onnistuneesti!';
                        header('Location: ../galleria.php?gallery='.$gallery_id.'&luonti=onnistui');
                        exit();

                    } else {
                        $_SESSION['gallery_create_success'] = 'Galleria luotu onnistuneesti!';
                        $_SESSION['cover_img_error'] = 'Kansikuvan latuas epäonnistui. Kuvatiedosto on liian suuri!';
                        header('Location: ../galleria.php?gallery='.$gallery_id.'&virhe=koko');
                        exit();
                    }
                } else {
                    $_SESSION['gallery_create_success'] = 'Galleria luotu onnistuneesti!';
                    $_SESSION['cover_img_error'] = 'Kansikuvan latuas epäonnistui. Vain .jpg, .jpeg tai .png sallittu!';
                    header('Location: ../galleria.php?gallery='.$gallery_id.'&virhe=tyyppi');
                    exit();
                }
            } else {
                $_SESSION['gallery_create_success'] = 'Galleria luotu onnistuneesti!';
                $_SESSION['cover_img_error'] = 'Kansikuvan latuas epäonnistui. Tiedoston latauksessa tapahtui virhe!';
                header('Location: ../galleria.php?gallery='.$gallery_id.'&virhe=lataus');
                exit();
            }
            
        } 

        // Asetetaan galleria luoduksi
        $_SESSION['gallery_create_success'] = 'Galleria luotu onnistuneesti!';
        header('Location: ../lisaa_galleria.php?luonti=onnistui&ei=kansikuvaa');
        exit();     

    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }

} else {
    header('Location: index.php?pääsy=kielletty');
    exit();
}