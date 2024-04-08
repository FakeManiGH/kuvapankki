<?php

include '../includes/config_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_logged_in()) {
    if (isset($_POST['user_id'])) {

        include '../includes/connections.php';
        include '../includes/profile_ctrl.php';
        include '../includes/profile_model.php';
        include '../includes/regex.php';

        $user_id = trim_input($_POST['user_id']);
        $username = trim_input($_POST['username']);
        $first_name = trim_input($_POST['first_name']);
        $last_name = trim_input($_POST['last_name']);
        $email = $_POST['email'];
        $phone = trim_input($_POST['phone']);
        $pwd = trim_input($_POST['pwd']);

        // Create array for errors
        $errors = [];

        // Check if the fields are empty
        if (empty($username) || empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($pwd)) {
            $errors['fields'] = 'Täytä kaikki kentät.';
        }

        // Check if the username is valid
        if (!preg_match($patternUser, $username)) {
            $errors['username'] = 'Käyttäjätunnus on virheellinen (5-35 merkkiä).';
        }

        // Check if the first name is valid
        if (!preg_match($patternFirst, $first_name)) {
            $errors['first_name'] = 'Etunimi sisältää kiellettyjä merkkejä (2-50 merkkiä).';
        }

        // Check if the last name is valid
        if (!preg_match($patternLast, $last_name)) {
            $errors['last_name'] = 'Sukunimi sisältää kiellettyjä merkkejä (2-50 merkkiä).';
        }

        // Check if the phone number is valid
        if (!preg_match($patternPhone, $phone)) {
            $errors['phone'] = 'Puhelinnumero on virheellinen (7-15 numeroa).';
        }

        // Check if the email is valid
        if (!preg_match($patternEmail, $email)) {
            $errors['email'] = 'Sähköposti on väärässä muodossa.';
        }

        try {
            // Check if the username is already in use
            if (username_exists($pdo, $username) && $username !== $_SESSION['username']) {
                $errors['username'] = 'Käyttäjätunnus on jo käytössä.';
            }
            // Check if the email is already in use
            if (email_exists($pdo, $email) && $email !== $_SESSION['email']) {
                $errors['email'] = 'Sähköposti on jo käytössä.';
            }

            // Check if the password is correct
            $result = get_password($pdo, $user_id);
            if (!password_verify($pwd, $result['pwd'])) {
                $errors['pwd'] = 'Salasana on virheellinen.';
            }

            // If there are no errors, update the user's information
            if (empty($errors)) {
                update_profile($pdo, $user_id, $username, $first_name, $last_name, $phone, $email);

                // Close the connection
                $pdo = null;

                // Update the session variables
                $_SESSION['username'] = $username;
                $_SESSION['first_name'] = $first_name;
                $_SESSION['last_name'] = $last_name;
                $_SESSION['email'] = $email;

                $updated = array(
                    'username' => $username,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'phone' => $phone,
                    'email' => $email
                );

                // Create a success message
                $data = array(
                    'success' => true,
                    'message' => 'Tiedot päivitetty.',
                    'updated' => $updated
                );

                header('Content-Type: application/json utf-8');
                echo json_encode($data);
                exit();

            } else {

                $data = array(
                    'success' => false,
                    'error' => 'Tietojen päivitys epäonnistui.',
                    'errors' => $errors
                );

                header('Content-Type: application/json utf-8');
                echo json_encode($data);
                exit();
            }   

        } catch (PDOException $e) {

            $data = array(
                'success' => false,
                'error' => 'Tietojen päivitys epäonnistui: ' . $e->getMessage() . '.'
            );
            header('Content-Type: application/json');
            echo json_encode($data);
            exit();
        }

    } else {
        
        $data = array(
            'success' => false,
            'error' => 'Tietojen päivitys epäonnistui.'
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