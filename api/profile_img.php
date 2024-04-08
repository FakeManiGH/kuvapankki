<?php

include '../includes/config_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['userfile'])) {
        $user_id = htmlspecialchars($_POST['user_id']);
        $file = $_FILES['userfile'];

        // Handle the uploaded file here
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];
        
        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));

        $allowed = ['jpg', 'jpeg', 'png'];

        // Check if the file type is allowed
        if (!in_array($fileActualExt, $allowed)) {
            $data = array(
                'success' => false,
                'error' => 'Tiedostotyyppi '. $fileActualExt .' ei ole sallittu.',
            );
            header('Content-Type: application/json');
            echo json_encode($data);
            exit();
        }

        // Check if the file size is allowed
        include '../includes/images_ctrl.php';
        if (!check_file_size($fileSize)) {
            $data = array(
                'success' => false,
                'error' => 'Tiedosto on liian suuri ('. $fileSize .').',
            );
            header('Content-Type: application/json');
            echo json_encode($data);
            exit();
        }

        try {
            include '../includes/connections.php';
            include '../includes/profile_model.php';

            // Finilize the file name and destination
            $fileNameNew = $user_id . "." . $fileActualExt;
            $fileDest = '../profile_images/' . $fileNameNew;
            $fileDestDB = 'profile_images/' . $fileNameNew;

            // Move the file to the destination
            move_uploaded_file($fileTmpName, $fileDest);

            // Update the profile image in the database
            update_profile_img($pdo, $user_id, $fileDestDB);

            $pdo = null;

            // Return a success message
            $data = array(
                'success' => true,
                'message' => 'Profiilikuva päivitetty onnistuneesti!',
            );
            header('Content-Type: application/json');
            echo json_encode($data);
            exit();
    
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            $data = array(
                'success' => false,
                'error' => 'Virhe tietokantayhteydessä.',
            );
            header('Content-Type: application/json');
            echo json_encode($data);
            exit();
        }

    } else {

        $data = array(
            'success' => false,
            'error' => 'Tiedostoa ei valittu.',
        );
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

} else {

    $_SESSION['404_error'] = "Sivua ei löytynyt tai sinulla ei ole siihen oikeutta.";
    header('Location: ../404.php?virhe');
    die();
}