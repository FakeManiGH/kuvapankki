<?php

declare(strict_types=1);

function is_username_taken(object $pdo, string $username) {
    $query = "SELECT username FROM users WHERE username = :username LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute([$username]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function is_email_taken(object $pdo, string $email) {
    $query = "SELECT email FROM users WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute([$email]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function create_user(object $pdo, string $username, string $first_name, string $last_name, int $phone, string $email, string $hashedPwd, string $email_token) {
    $query = "INSERT INTO users (username, first_name, last_name, phone, email, pwd, email_token) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username, $first_name, $last_name, $phone, $email, $hashedPwd, $email_token]);
}

function get_email_token(object $pdo, string $token) {
    $query = "SELECT email_token, verified FROM users WHERE email_token = :token LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':token', $token);
    $stmt->execute([$token]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function verify_email(object $pdo, string $token) {
    $query = "UPDATE users SET verified = 1 WHERE email_token = :token";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':token', $token);
    $stmt->execute([$token]);
}