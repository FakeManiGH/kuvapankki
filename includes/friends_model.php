<?php

declare(strict_types=1);

// Hakee käyttäjät tietokannasta
function search_users(object $pdo, string $search_input) {
    $query = "SELECT username, user_id FROM users WHERE username LIKE :search_input LIMIT 10";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':search_input', $search_input);
    $stmt->execute();

    $user_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $user_list;
}

// Tarkistetaan onko käyttäjä olemassa | boolean
function user_exists(object $pdo, int $user_id) {
    $query = "SELECT * FROM users WHERE user_id = :user_id LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        return true;
    } else {
        return false;
    }
}



// Hakee kaverit tietokannasta
function get_friends(object $pdo, int $user_id) {
    $query = "SELECT users.user_id, users.username, users.user_img FROM friends 
              JOIN users ON friends.friend = users.user_id 
              WHERE friends.user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

// Tarkistaa onko kaveripyyntö jo lähetetty
function request_already_sent(object $pdo, int $your_id, int $friend_id) {
    $query = "SELECT * FROM friend_requests WHERE your_id = :user_id AND friend_id = :friend_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $your_id);
    $stmt->bindParam(':friend_id', $friend_id);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        return true;
    } else {
        return false;
    }
}

// Lähettää kaveripyyntö
function send_friend_request(object $pdo, int $your_id, int $friend_id) {
    $query = "INSERT INTO friend_requests (your_id, friend_id) VALUES (:your_id, :friend_id)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':your_id', $your_id);
    $stmt->bindParam(':friend_id', $friend_id);
    $stmt->execute();
}

// Hakee saapuneet kaveripyynnöt tietokannasta
function get_incoming_requests(object $pdo, int $user_id) {
    $query = "SELECT * FROM friend_requests 
              JOIN users ON friend_requests.your_id = users.user_id 
              WHERE friend_requests.friend_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

// Hakee lähetetyt kaveripyynnöt tietokannasta
function get_sent_requests(object $pdo, int $user_id) {
    $query = "SELECT * FROM friend_requests 
              JOIN users ON friend_requests.friend_id = users.user_id 
              WHERE friend_requests.your_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

// Tarkistaa onko käyttäjä jo kaveri
function is_friend(object $pdo, int $your_id, int $friend_id) {
    $query = "SELECT * FROM friends WHERE user_id = :your_id AND friend = :friend_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':your_id', $your_id);
    $stmt->bindParam(':friend_id', $friend_id);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        return true;
    } else {
        return false;
    }
}

// Peruu kaveripyynnön
function cancel_request(object $pdo, int $your_id, int $friend_id) {
    $query = "DELETE FROM friend_requests WHERE your_id = :your_id AND friend_id = :friend_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':your_id', $your_id);
    $stmt->bindParam(':friend_id', $friend_id);
    $stmt->execute();
}

// Hyväksyy kaveripyynnön
function add_friend(object $pdo, int $your_id, int $friend_id) {
    $query = "INSERT INTO friends (user_id, friend) VALUES (:your_id, :friend_id)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':your_id', $your_id);
    $stmt->bindParam(':friend_id', $friend_id);
    $stmt->execute();
}

// Poistaa kaverin
function remove_friend(object $pdo, int $your_id, int $friend_id) {
    $query = "DELETE FROM friends WHERE user_id = :your_id AND friend = :friend_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':your_id', $your_id);
    $stmt->bindParam(':friend_id', $friend_id);
    $stmt->execute();
}

// Poistaa kaverin myös toiselta käyttäjältä
function remove_friend_from_friend(object $pdo, int $your_id, int $friend_id) {
    $query = "DELETE FROM friends WHERE user_id = :friend_id AND friend = :your_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':friend_id', $friend_id);
    $stmt->bindParam(':your_id', $your_id);
    $stmt->execute();
}