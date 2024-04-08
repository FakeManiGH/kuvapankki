<?php

declare(strict_types=1);

function get_images(object $pdo, int $user_id): array {
    $query = 'SELECT * FROM images WHERE user_id = :user_id';
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $user_id]);
    
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $images;
}

function get_subscription(object $pdo, int $user_id): array {
    $query = 'SELECT * FROM subscriptions WHERE user_id = :user_id';
    $stmt = $pdo->prepare($query);
    $stmt->execute(['user_id' => $user_id]);
    
    $subscription = $stmt->fetch(PDO::FETCH_ASSOC);
    return $subscription;
}