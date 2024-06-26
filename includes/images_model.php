<?php

declare(strict_types=1);

// function to check if gallery exists
function gallery_exists(object $pdo, int $gallery_id): bool {
    $query = "SELECT gallery_id FROM galleries WHERE gallery_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$gallery_id]);
    $gallery = $stmt->fetch();
    if ($gallery) {
        return true;
    } else {
        return false;
    }
}

// function to check if user has write access to gallery
function has_write_access(object $pdo, int $user_id, int $gallery_id): bool {
    $query = "SELECT role_id FROM gallery_users WHERE user_id = ? AND gallery_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user_id, $gallery_id]);
    $role = $stmt->fetch();
    if ($role['role_id'] == 1 || $role['role_id'] == 2) {
        return true;
    } else {
        return false;
    }
}

// add image to database
function add_image(object $pdo, $fileNameNew, int $post_id, string $title, string $description, string $fileDestDB, int $fileSize, int $user_id, int $gallery_id) {
    $query = "INSERT INTO images (file_name, post_id, title, description, url, size, user_id, gallery_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$fileNameNew, $post_id, $title, $description, $fileDestDB, $fileSize, $user_id, $gallery_id]);
}



// IMAGE UPDATE

// function to check if image exists
function image_exists(object $pdo, int $image_id): bool {
    $query = "SELECT image_id FROM images WHERE image_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$image_id]);
    $image = $stmt->fetch();
    if ($image) {
        return true;
    } else {
        return false;
    }
}

// function to update image
function update_image(object $pdo, int $image_id, string $title, string $description) {
    $query = "UPDATE images SET title = ?, description = ? WHERE image_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$title, $description, $image_id]);
}



// IMAGE DELETE

// function to delete image
function delete_image(object $pdo, int $image_id) {
    $query = "DELETE FROM images WHERE image_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$image_id]);
}
