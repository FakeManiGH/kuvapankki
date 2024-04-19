<?php

declare(strict_types=1);

// Search users from database by username
function search_users(object $pdo, string $search_input, int $user_id) {
    $query = "SELECT username, user_id FROM users WHERE username LIKE :search_input AND user_id != :user_id LIMIT 10";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':search_input', $search_input);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $users;
}

// Get user by name from database
function get_user_by_name(object $pdo, string $username) {
    $query = "SELECT user_id, username FROM users WHERE username = :username LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user;
}
