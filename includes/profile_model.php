<?php

declare(strict_types=1);

// Hakee käyttäjän tiedot tietokannasta
function get_user(object $pdo, int $user_id) {
    $query = "SELECT * FROM users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

// Hakee käyttäjän salasanan tietokannasta
function get_password(object $pdo, int $user_id) {
    $query = "SELECT pwd FROM users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

// Tarkistaa onko käyttäjätunnus jo käytössä
function username_exists($pdo, $username) {
    $query = "SELECT username FROM users WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

// Tarkistaa onko sähköposti jo käytössä
function email_exists($pdo, $email) {
    $query = "SELECT email FROM users WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}

// Päivittää käyttäjän tiedot tietokantaan
function update_profile($pdo, $user_id, $username, $first_name, $last_name, $phone, $email) {
    $query = "UPDATE users SET username = :username, first_name = :first_name, last_name = :last_name, phone = :phone, email = :email WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
}

// Päivittää käyttäjän profiilikuvan tietokantaan
function update_profile_img($pdo, $user_id, $image_name_new) {
    $query = "UPDATE users SET user_img = :user_img WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_img', $image_name_new);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
}