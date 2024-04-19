<?php

include '../includes/config_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_logged_in()) {
    if (isset($_POST['description']) && isset($_POST['gallery'])) {

        include '../includes/connections.php';
        include '../includes/images_ctrl.php';
        include '../includes/images_model.php';
        include '../includes/posts_model.php';

        $description = trim_input($_POST['description']);
        $gallery_id = trim_input($_POST['gallery']);
        $images = $_FILES['images'];
        $user_id = $_SESSION['user_id'];

        $errors = [];

        // Check if gallery is selected
        if (empty($gallery_id)) {
            $errors['gallery'] = 'Valitse galleria kuville. Jos sinulla ei ole gallerioita, luo sellainen ensin.';
        }

        // Check if description is empty
        if (empty($description)) {
            $errors['description'] = 'Julkaisun kuvaus puuttuu.';
        }

        // Check if files were selected
        if (empty($images['name'][0])) {
            $errors['images'] = 'Kuvia ei valittu.';
        }

        // If errors at this point, return.
        if (count($errors) > 0) {
            $data = array(
                'success' => false,
                'errors' => $errors
            );
            header('Content-Type: application/json');
            echo json_encode($data);
            exit();
        }

        try {
            // Check if gallery exists
            if (!gallery_exists($pdo, $gallery_id)) {
                $errors['gallery'] = 'Galleriaa ei löydy.';
            }

            // Check if user has write access to gallery
            if (!has_write_access($pdo, $user_id, $gallery_id)) {
                $errors['gallery'] = 'Sinulla ei ole oikeuksia tähän galleriaan.';
            }

            // If errors at this point, return.
            if (count($errors) > 0) {
                $data = array(
                    'success' => false,
                    'errors' => $errors
                );
                header('Content-Type: application/json');
                echo json_encode($data);
                exit();
            }

            // Create a new post
            create_post($pdo, $user_id, $gallery_id, $description);

            // Get the last inserted post id
            $post_id = $pdo->lastInsertId();

            for ($i = 0; $i < count($images['name']); $i++) {
                try {
                    $pdo->beginTransaction();

                    // Access the file properties
                    $fileName = $images['name'][$i];
                    $fileTmpName = $images['tmp_name'][$i];
                    $fileSize = $images['size'][$i];
                    $fileError = $images['error'][$i];
                    $fileType = $images['type'][$i];

                    // Check if there is title and description for image
                    $title = isset($_POST['title'][$i]) ? htmlspecialchars($_POST['title'][$i]) : ' ';
                    $img_description = isset($_POST['description'][$i]) ? htmlspecialchars($_POST['description'][$i]) : ' ';

                    // Check if file was selected
                    if (empty($fileName)) {
                        $errors['images'] = 'Tiedostoa ei valittu.';
                        continue;
                    }

                    // Check if file is an image
                    $fileExt = explode('.', $fileName);
                    $fileActualExt = strtolower(end($fileExt));

                    if (!check_file_type($fileActualExt)) {
                        $errors['images'] = 'Tiedosto ' .$fileName. 'ei ole kuva.';
                        continue;
                    }

                    // Check if file size is under 5MB
                    if (!check_file_size($fileSize)) {
                        $errors['images'] = 'Tiedosto ' .$fileName. 'on liian suuri.';
                        continue;
                    }

                    // Create a new file name
                    $fileNameNew = uniqid('', true) . "." . $fileActualExt;

                    // Create a file destination
                    $uploadDir = '../../galleries/' .$gallery_id. '/';
                    $fileDest = $uploadDir . $fileNameNew;
                    $filePath = $gallery_id . '/' . $fileNameNew;

                    // Move file to destination
                    move_uploaded_file($fileTmpName, $fileDest);

                    // Add image to database
                    add_image($pdo, $fileNameNew, $post_id, $title, $img_description, $filePath, $fileSize, $user_id, $gallery_id);

                    // Check if image was added to database
                    if (!image_exists($pdo, $pdo->lastInsertId())) {
                        $errors['images'] = 'Kuvan ' .$fileName. ' lisäys epäonnistui.';
                        continue;
                    }

                    // Check if file was moved to destination
                    if (!file_exists($fileDest)) {
                        $errors['images'] = 'Tiedoston ' .$fileName. ' siirto epäonnistui.';
                        continue;
                    }

                    if (count($errors) > 0) {
                        $pdo->rollBack();
                    } else {
                        $pdo->commit();
                    }

                } catch (PDOException $e) {
                    $pdo->rollBack();
                    error_log($e->getMessage(), 3, "../../logs/image_errors.log");
                    $data = array(
                        'success' => false,
                        'error' => 'Julkaisu epäonnistui: ' . $e->getMessage() . '.',
                        'errors' => $errors
                    );
                    header('Content-Type: application/json');
                    echo json_encode($data);
                    $pdo = null;
                    exit();
                }
            }

        } catch (PDOException $e) {
            error_log($e->getMessage(), 3, "../../logs/image_errors.log");
            $data = array(
                'success' => false,
                'error' => 'Julkaisu epäonnistui: ' . $e->getMessage() . '.'
            );
            header('Content-Type: application/json');
            echo json_encode($data);
            $pdo = null;
            exit();
        }

        // Set success message
        $data = array(
            'success' => true,
            'message' => 'Julkaisu onnistui.',
            'errors' => $errors
        );
        header('Content-Type: application/json');
        echo json_encode($data);
        $pdo = null;
        exit();

    } else {
        $data = array(
            'success' => false,
            'error' => 'Julkaisu epäonnistui. Tarkista tiedot ja yritä uudelleen.'
        );
        header('Content-Type: application/json');
        echo json_encode($data);
        $pdo = null;
        exit();
    }

} else {
    
    $_SESSION['404_error'] = "Sivua ei löytynyt tai sinulla ei ole siihen oikeutta.";
    header('Location: ../404.php?virhe');
    die();
}