<?php

include '../includes/config_session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && is_logged_in()) {

    // Get data from the client (clean)
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $category = intval($_POST['category']);
    $tags = htmlspecialchars($_POST['tags']);
    $type = intval($_POST['type']);
    $visibility = intval($_POST['visibility']);
    $owner_id = $_SESSION['user_id'];

    try {
        include '../includes/connections.php';
        require '../includes/gallery_ctrl.php';
        require '../includes/gallery_model.php';
        require '../includes/regex.php';

        $errors = [];
        $corr_values = [];

        // Check if category is valid
        if ($category == 0) {
            $errors['category'] = 'Valitse gallerialle kategoria.';
        } else {
            $corr_values['category'] = $category;
        }

        // Check if the visibility is valid
        if (is_in_range($visibility, 1, 3) === false) {
            $errors['visibility'] = 'Valitse gallerialle näkyvyys.';
        } else {
            $corr_values['visibility'] = $visibility;
        }

        // Check if the type is valid
        if (is_in_range($type, 1, 4) === false) {
            $errors['type'] = 'Valitse gallerialle sopiva tyyppi.';
        } else {
            $corr_values['type'] = $type;
        }

        // Check if the name is valid
        if (!preg_match($patternGallery, $name)) {
            $errors['name'] = 'Nimen tulee olla 1-75 merkkiä pitkä.';
        } else {
            $corr_values['name'] = $name;
        }

        // Check if the name is unique
        if (gallery_name_exists($pdo, $name)) {
            $errors['name'] = 'Gallerian nimi on jo käytössä.';
        } else {
            $corr_values['name'] = $name;
        }

        // Check if the description is valid
        if (!preg_match($patternDesc, $description)) {
            $errors['description'] = 'Kuvauksen tulee olla 1-400 merkkiä pitkä.';
        } else {
            $corr_values['description'] = $description;
        }

        // Check if the tags are valid
        if (!validateTags($tags)) {
            $errors['tags'] = 'Tageja tulee olla 1-15 kappaletta.';
        } else {
            $corr_values['tags'] = $tags;
        }

        // If there are no errors, create a new gallery
        if (empty($errors)) {

            try {
                $pdo->beginTransaction();

                // Create a new gallery ID
                $gallery_id = create_gallery_id($pdo);

                // Create a new gallery
                create_gallery($pdo, $gallery_id, $owner_id, $name, $description, $visibility, $tags, $category, $type);

                // Create a new group user
                create_gallery_user($pdo, $gallery_id, $owner_id, 1);

                // Get selected users from post
                $users = $_POST['selected_users'];

                // Create a new gallery invite for each selected user
                if (!empty($users)) {
                    foreach ($users as $user) {
                        create_gallery_invite($pdo, $gallery_id, $owner_id, $user);
                    }
                }

                // Create a new folder for the gallery
                $gallery_dir = '../../galleries/'.$gallery_id;
                mkdir($gallery_dir, 0777, true);

                $pdo->commit();

                $_SESSION['gallery_create_success'] = 'Galleria luotu onnistuneesti!';
                $data = [
                    'success' => true,
                    'gallery_id' => $gallery_id,
                ];
                header('Content-Type: application/json');
                echo json_encode($data);
                $pdo = null;
                exit();

            } catch (PDOException $e) {
                $errors['general'] = 'Gallerian luonti epäonnistui, yritä uudelleen.';
                error_log("PDOException: " . $e->getMessage());
                $data = [
                    'success' => false,
                    'errors' => $errors,
                    'correct' => $corr_values
                ];
                header('Content-Type: application/json');
                echo json_encode($data);
                die();
            }
            
        } else {
            $data = [
                'success' => false,
                'errors' => $errors,
                'correct' => $corr_values
            ];
            header('Content-Type: application/json');
            echo json_encode($data);
            $pdo = null;
            exit();
        }

    } catch (PDOException $e) {
        $errors['general'] = 'Gallerian luonti epäonnistui, yritä uudelleen.';
        error_log("PDOException: " . $e->getMessage());
        $data = [
            'success' => false,
            'errors' => $errors,
            'correct' => $corr_values
        ];
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }
}