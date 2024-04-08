<?php

require_once '../includes/config_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email'])) {

        include '../includes/connections.php';
        include '../includes/pwd_reset_model.php';
        include '../includes/pwd_reset_ctrl.php';
        include '../includes/profile_model.php';
        require_once '../includes/regex.php';
        require_once "../includes/send_email_model.php";

        $email = $_POST['email'];
        $pwd_token = md5(rand(25, 50));

        // Clean the created token for later comparison (for security reasons).
        $pwd_token = trim_input($pwd_token);

        // Create error array for error messages.
        $errors = [];

        // Check if the user's input is empty.
        if (empty($email)) {
            $errors['email'] = 'Sähköpostiosoite puuttuu.';
        }

        // Check if the user's input is in the correct format.
        if (!preg_match($patternEmail, $email)) {
            $errors['email'] = 'Sähköpostiosoite on väärässä muodossa.';
        }

        try {
            // Retrieve the user's information from the database.
            $user = get_user_by_email($pdo, $email);
            $user_id = $user['user_id'];
            $username = $user['username'];
            
            // Check if the email exists in the database.
            if (!email_exists($pdo, $email)) {
                $errors['email'] = 'Sähköpostiosoite ei ole rekisteröity.';
            }

            // If there are no errors, send the email.
            if (empty($errors)) {
                send_reset_email($username, $email, $pwd_token);

                // Insert the token into the database.
                create_reset_token($pdo, $user_id, $pwd_token);

                // Close the database connection.
                $pdo = null;

                $data = array(
                    'success' => true,
                    'message' => 'Salasanan nollauslinkki on lähetetty sähköpostiisi. Tarkista myös roskapostikansio.'
                );
                header('Content-Type: application/json');
                echo json_encode($data);
                exit();

            } else {
                $data = array(
                    'success' => false,
                    'error' => $errors
                );
                header('Content-Type: application/json');
                echo json_encode($data);
                exit();
            }

        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            $data = array(
                'success' => false,
                'error' => 'Virhe tietokantayhteydessä.'
            );
            header('Content-Type: application/json');
            echo json_encode($data);
            exit();
        }

    } else {
        $data = array(
            'success' => false,
            'error' => 'Sähköpostiosoite puuttuu.'
        );
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

} else {
    $_SESSION['404_error'] = "Sivua ei löytynyt tai sinulla ei ole siihen oikeutta.";
    header("Location: ../404.php?virhe");
    die();
}