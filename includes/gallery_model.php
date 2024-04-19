<?php

declare(strict_types=1);

// CREATING GALLERY

// Function to check if gallery name is unique
function gallery_name_exists(object $pdo, string $name) : bool {
    $query = "SELECT 1 FROM galleries WHERE name = :name";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->execute();

    $exists = $stmt->fetch(PDO::FETCH_COLUMN);
    $stmt->closeCursor();
    return $exists;
}

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
function create_gallery(object $pdo, int $gallery_id, int $owner_id, string $name, string $description, int $category, string $tags, int $type, int $visibility) {
    $stmt = $pdo->prepare('INSERT INTO galleries (gallery_id, owner_id, name, description, category, tags, type, visibility) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$gallery_id, $owner_id, $name, $description, $category, $tags, $type, $visibility]);
}

// Function to create a new group user
function create_gallery_user(object $pdo, int $gallery_id, int $owner_id, int $num) {
    $stmt = $pdo->prepare('INSERT INTO gallery_users (gallery_id, user_id, role_id) VALUES (?, ?, ?)');
    $stmt->execute([$gallery_id, $owner_id, $num]);
}

// Function to create a new gallery invite
function create_gallery_invite(object $pdo, int $gallery_id, int $owner_id, int $user_id) {
    $stmt = $pdo->prepare('INSERT INTO gallery_invites (gallery_id, sender_id, reciever_id) VALUES (?, ?, ?)');
    $stmt->execute([$gallery_id, $owner_id, $user_id]);
}

// Function to update a gallery cover image
function update_gallery_cover(object $pdo, int $gallery_id, string $cover_img_dest) {
    $query = "UPDATE galleries SET cover_img = :cover_img_dest 
    WHERE gallery_id = :gallery_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':cover_img_dest', $cover_img_dest); // Corrected here
    $stmt->bindParam(':gallery_id', $gallery_id);
    $stmt->execute();
}





// VIEWING GALLERIES

// Function to check if user is admin 
function is_gallery_admin(object $pdo, int $user_id, int $gallery_id) {
    $query = "SELECT role_id FROM gallery_users WHERE user_id = :user_id AND gallery_id = :gallery_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':gallery_id', $gallery_id);
    $stmt->execute();

    $role_id = $stmt->fetch(PDO::FETCH_COLUMN);
    $stmt->closeCursor();
    return $role_id === 1;
}

// Function to view private galleries
function view_owned_galleries(object $pdo, int $user_id) {
    $query = "SELECT g.gallery_id, g.name, g.description, g.cover_img, g.visibility, g.created, u.username 
    FROM galleries g JOIN users u ON g.owner_id = u.user_id 
    WHERE owner_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $galleries = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $galleries;
}

// Function to view joined galleries
function view_joined_galleries(object $pdo, int $user_id) {
    $query = "SELECT g.gallery_id, g.name, g.description, g.cover_img, g.visibility, g.created, gu.role_id, u.username 
    FROM galleries g JOIN gallery_users gu ON g.gallery_id = gu.gallery_id JOIN users u ON g.owner_id = u.user_id 
    WHERE gu.user_id = :user_id1 AND g.owner_id != :user_id2";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id1', $user_id);
    $stmt->bindParam(':user_id2', $user_id);
    $stmt->execute();

    $galleries = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $galleries;
}

// Function to get all gallery details
function get_gallery_details(object $pdo, int $gallery_id) {
    $query = "SELECT g.gallery_id, g.name, g.owner_id, g.description, g.cover_img, g.visibility, g.created, g.updated, u.username as owner_username 
    FROM galleries g JOIN users u ON g.owner_id = u.user_id 
    WHERE gallery_id = :gallery_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':gallery_id', $gallery_id);
    $stmt->execute();

    $gallery = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $gallery;
}

// Function to get gallery members and their roles
function get_gallery_members(object $pdo, int $gallery_id) {
    $query = "SELECT gu.user_id, gu.role_id, u.username 
    FROM gallery_users gu JOIN users u ON gu.user_id = u.user_id 
    WHERE gallery_id = :gallery_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':gallery_id', $gallery_id);
    $stmt->execute();

    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $members;
}

// Function to get gallery member count
function get_gallery_member_count(object $pdo, int $gallery_id) {
    $query = "SELECT COUNT(user_id) 
    FROM gallery_users 
    WHERE gallery_id = :gallery_id";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':gallery_id', $gallery_id);
    $stmt->execute();

    $member_count = $stmt->fetch(PDO::FETCH_COLUMN);
    $stmt->closeCursor();
    return $member_count;
}

// Function to get gallery images
function get_gallery_images(object $pdo, int $gallery_id) {
    $query = "SELECT i.image_id, i.file_name, i.uploaded, i.title, i.description, i.url, i.user_id, u.username 
    FROM images i JOIN users u ON i.user_id = u.user_id 
    WHERE i.gallery_id = :gallery_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':gallery_id', $gallery_id);
    $stmt->execute();
    
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $images;
}





// FOR ADDING IMAGES

// Function to get write access galleries
function get_write_access_galleries(object $pdo, int $user_id) {
    $query = "SELECT DISTINCT g.gallery_id, g.name, g.visibility, g.owner_id, gu.role_id, u.username as owner_username 
    FROM galleries g 
    JOIN gallery_users gu ON g.gallery_id = gu.gallery_id 
    JOIN users u ON g.owner_id = u.user_id 
    WHERE gu.user_id = :user_id AND gu.role_id != 3";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $galleries = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $galleries;
}



// DELETING CONTENT


