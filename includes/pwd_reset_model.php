<?php

declare(strict_types=1);

function get_user(object $pdo, string $userinfo) {
    $query = "SELECT username, email, user_id FROM users WHERE username = :username OR email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $userinfo);
    $stmt->bindParam(':email', $userinfo);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function create_reset_token(object $pdo, int $user_id, string $pwd_token) {
    $query = "INSERT INTO pwd_tokens (user_id, pwd_token) VALUES (:user_id, :pwd_token)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':pwd_token', $pwd_token);
    $stmt->execute();
}