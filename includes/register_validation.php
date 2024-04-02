
<?php

$username = $first_name = $last_name = $phone = $email = $pwd = $pwd2 = '';
$usernameErr = $first_nameErr = $last_nameErr = $phoneErr = $emailErr = $pwdErr = $pwd2Err = '';

// Regular expressions for input validation
include 'regex.php';


// form validation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];
    $pwd2 = $_POST['pwd2'];


    // sanitize input
    function trim_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // validate username
    if (empty($_POST['username'])) {
        $usernameErr = 'Käyttäjätunnus on pakollinen';
    } else {
        $username = trim_input($_POST['username']);
        if (!preg_match($patternUser, $username)) {
            $usernameErr = 'Käytä 5-35 merkkiä';
        }
        if (empty($usernameErr)) {
            try {
                require_once "includes/connections.php";
                require_once "includes/register_model.php";
                $result = is_username_taken($pdo, $username);
                if ($result)
                    $usernameErr = 'Käyttäjätunnus on jo käytössä';
            } catch (PDOException $e) {
                die("Tietokantavirhe: " . $e->getMessage());
            }
        }
    }
    

    // validate first name
    if (empty($_POST['first_name'])) {
        $first_nameErr = 'Etunimi on pakollinen';
    } else {
        $first_name = trim_input($_POST['first_name']);
        if (!preg_match($patternFirst, $first_name)) {
            $first_nameErr = 'Käytä vain kirjaimia ja välilyöntejä. Enintään 50 merkkiä';
        }
    }

    // validate last name
    if (empty($_POST['last_name'])) {
        $last_nameErr = 'Sukunimi on pakollinen';
    } else {
        $last_name = trim_input($_POST['last_name']);
        if (!preg_match($patternLast, $last_name)) {
            $last_nameErr = 'Käytä vain kirjaimia ja välilyöntejä. Enintään 50 merkkiä';
        }
    }

    // validate phonenumber
    if (empty($_POST['phone'])) {
        $phoneErr = 'Puhelinnumero on pakollinen';
    } else {
        $phone = trim_input($_POST['phone']);
        if (!preg_match($patternPhone, $phone)) {
            $phoneErr = 'Puhelinnumeron tulee olla 7-15 numeroa pitkä';
        }
    }

    // validate email
    if (empty($_POST['email'])) {
        $emailErr = 'Sähköpostiosoite on pakollinen';
    } else {
        $email = trim_input($_POST['email']);
        if (!preg_match($patternEmail, $email)) {
            $emailErr = 'Anna kelvollinen sähköpostiosoite';
        }
        if (empty($emailErr)) {
            try {
                require_once "includes/connections.php";
                require_once "includes/register_model.php";
                $result = is_email_taken($pdo, $email);
                if ($result)
                    $emailErr = 'Sähköpostiosoite on jo käytössä';
                $pdo = null;
                $stmt = null;
            } catch (PDOException $e) {
                die("Tietokantavirhe: " . $e->getMessage());
            }
        }
    }

    // validate password
    if (empty($_POST['pwd'])) {
        $pwdErr = 'Salasana on pakollinen';
    } else {
        $pwd = trim_input($_POST['pwd']);
        if (!preg_match($patternPwd, $pwd)) {
            $pwdErr = 'Salasanan tulee olla vähintään 12 merkkiä pitkä';
        }
    }

    // validate password2
    if (empty($_POST['pwd2'])) {
        $pwd2Err = 'Salasanan vahvistus on pakollinen';
    } else {
        $pwd2 = trim_input($_POST['pwd2']);
        if (!preg_match($patternPwd2, $pwd2)) {
            $pwd2Err = 'Salasanat eivät täsmää';
        }
    } 
}

