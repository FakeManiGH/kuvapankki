<?php

declare(strict_types=1);

// CREATING GALLERY

// Function to create a new gallery ID
function create_gallery_id(object $pdo) {
    do {
        $gallery_id = rand(100000, 999999);
        $stmt = $pdo->prepare("SELECT 1 FROM galleries WHERE gallery_id = ?");
        $stmt->execute([$gallery_id]);
    } while ($stmt->fetch(PDO::FETCH_COLUMN));

    return $gallery_id;
}

// Function to create a new gallery
function create_gallery(object $pdo, int $gallery_id, int $owner_id, string $name, string $description, int $visibility, string $tags) {
    $stmt = $pdo->prepare('INSERT INTO galleries (gallery_id, owner_id, name, description, visibility, tags) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$gallery_id, $owner_id, $name, $description, $visibility, $tags]);
}

// Function to create a new group user
function create_gallery_user(object $pdo, int $gallery_id, int $owner_id, int $num) {
    $stmt = $pdo->prepare('INSERT INTO gallery_users (gallery_id, user_id, role_id) VALUES (?, ?, ?)');
    $stmt->execute([$gallery_id, $owner_id, $num]);
}

// Function to update a gallery cover image
function update_gallery_cover(object $pdo, int $gallery_id, string $cover_img_dest) {
    $query = "UPDATE galleries SET cover_img = :cover_img_dest WHERE gallery_id = :gallery_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':cover_img_dest', $cover_img_dest); // Corrected here
    $stmt->bindParam(':gallery_id', $gallery_id);
    $stmt->execute();
}



// VIEWING GALLERIES

// Function to view private galleries
function view_owned_galleries(object $pdo, int $user_id) {
    $query = "SELECT gallery_id, name, cover_img, visibility FROM galleries WHERE owner_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $galleries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $galleries;
}

// Function to view joined galleries
function view_joined_galleries(object $pdo, int $user_id) {
    $query = "SELECT g.gallery_id, g.name, g.cover_img, g.visibility, gu.role_id FROM galleries g JOIN gallery_users gu ON g.gallery_id = gu.gallery_id WHERE gu.user_id = :user_id1 AND g.owner_id != :user_id2";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id1', $user_id);
    $stmt->bindParam(':user_id2', $user_id);
    $stmt->execute();

    $galleries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $galleries;
}
