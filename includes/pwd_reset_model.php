<?php

declare(strict_types=1);

// Find user with email
function get_user(object $pdo, string $email) {
    $query = "SELECT username, email, user_id FROM users WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

// Create a new password reset token
function create_reset_token(object $pdo, int $user_id, string $pwd_token) {
    $query = "INSERT INTO pwd_tokens (user_id, token) VALUES (:user_id, :pwd_token)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':pwd_token', $pwd_token);
    $stmt->execute();
}


// FOLLOWING FUNCTIONS ARE FOR AFTER THE USER HAS RECEIVED THE RESET LINK

// Get token from database
function get_token(object $pdo, string $pwd_token) {
    $query = "SELECT token FROM pwd_tokens WHERE token = :pwd_token";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':pwd_token', $pwd_token);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

// Get user with token
function get_token_info(object $pdo, string $pwd_token) {
    $query = "SELECT user_id, token FROM pwd_tokens WHERE token = :pwd_token";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':pwd_token', $pwd_token);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

// Update user's password
function update_password(object $pdo, int $user_id, string $pwd) {
    $query = "UPDATE users SET pwd = :pwd WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':pwd', $pwd);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
}

// Delete token from database
function delete_token(object $pdo, int $user_id) {
    $query = "DELETE FROM pwd_tokens WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
}