<?php

declare(strict_types=1);

// Function to create new post to database
function create_post(object $pdo, int $user_id, int $gallery_id, string $description) {
    $query = "INSERT INTO posts (user_id, gallery_id, description) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user_id, $gallery_id, $description]);
}


// POST VIEW FUNCTIONS

// Function to get galleies where user is member
function get_galleries_where_member(object $pdo, int $user_id) {
    $query = "SELECT gallery_id FROM gallery_users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $galleries = $stmt->fetchAll();
    return $galleries;
}


// Function to get posts from galleries where user is member
function get_posts_from_galleries(object $pdo, array $galleries) {
    $gallery_ids = array_column($galleries, 'gallery_id');
    $placeholders = implode(',', array_fill(0, count($gallery_ids), '?'));
    $query = "SELECT p.post_id, p.description, p.user_id, p.date, g.gallery_id, g.name, u.username, u.user_img
              FROM posts p 
              JOIN galleries g ON p.gallery_id = g.gallery_id
              JOIN users u ON p.user_id = u.user_id 
              WHERE g.gallery_id IN ($placeholders)";
    $stmt = $pdo->prepare($query);
    $stmt->execute($gallery_ids);
    $posts = $stmt->fetchAll();
    return $posts;
}

// Function to get post likes and check if user has liked posts
function get_post_likes_and_user_likes(object $pdo, array $posts, int $user_id) {
    if (empty($posts)) {
        return [];
    }
    $post_ids = array_column($posts, 'post_id');
    $placeholders = implode(',', array_fill(0, count($post_ids), '?'));
    $query = "SELECT *, user_id = ? AS liked FROM post_likes WHERE post_id IN ($placeholders)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(array_merge([$user_id], $post_ids));
    $likes = $stmt->fetchAll();

    $likes_by_post_id = [];
    $user_likes_by_post_id = [];
    foreach ($likes as $like) {
        $likes_by_post_id[$like['post_id']][] = $like;
        if ($like['liked']) {
            $user_likes_by_post_id[$like['post_id']] = true;
        }
    }

    $posts_with_likes_and_user_likes = [];
    foreach ($posts as $post) {
        $post['likes'] = $likes_by_post_id[$post['post_id']] ?? [];
        $post['liked'] = isset($user_likes_by_post_id[$post['post_id']]);
        $posts_with_likes_and_user_likes[] = $post;
    }

    return $posts_with_likes_and_user_likes;
}


// Function to get images for posts
function get_images_for_posts(object $pdo, array $posts) {
    if (empty($posts)) {
        return [];
    }
    $post_ids = array_column($posts, 'post_id');
    $placeholders = implode(',', array_fill(0, count($post_ids), '?'));
    $query = "SELECT * FROM images WHERE post_id IN ($placeholders)";
    $stmt = $pdo->prepare($query);
    $stmt->execute($post_ids);
    $images = $stmt->fetchAll();

    $images_by_post_id = [];
    foreach ($images as $image) {
        $images_by_post_id[$image['post_id']][] = $image;
    }

    $posts_with_images = [];
    foreach ($posts as $post) {
        $post['images'] = $images_by_post_id[$post['post_id']] ?? [];
        $posts_with_images[] = $post;
    }

    return $posts_with_images;
}

// Function to get comments for posts
function get_comments_for_posts(object $pdo, array $posts) {
    if (empty($posts)) {
        return [];
    }
    $post_ids = array_column($posts, 'post_id');
    $placeholders = implode(',', array_fill(0, count($post_ids), '?'));
    $query = "SELECT c.comment_id, c.post_id, c.user_id, c.comment, c.date, u.username, u.user_img
              FROM post_comments c
              JOIN users u ON c.user_id = u.user_id
              WHERE c.post_id IN ($placeholders)";
    $stmt = $pdo->prepare($query);
    $stmt->execute($post_ids);
    $comments = $stmt->fetchAll();

    $comments_by_post_id = [];
    foreach ($comments as $comment) {
        $comments_by_post_id[$comment['post_id']][] = $comment;
    }

    $posts_with_comments = [];
    foreach ($posts as $post) {
        $post['comments'] = $comments_by_post_id[$post['post_id']] ?? [];
        $posts_with_comments[] = $post;
    }

    return $posts_with_comments;
}



// POST LIKES FUNCTIONS

// Function to check if post exists
function post_exists(object $pdo, int $post_id): bool {
    $query = "SELECT post_id FROM posts WHERE post_id = :post_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();
    $post = $stmt->fetch();
    return $post ? true : false;
}

// Function to check if like exists
function like_exists(object $pdo, int $post_id, int $user_id): bool {
    $query = "SELECT * FROM post_likes WHERE post_id = :post_id AND user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $like = $stmt->fetch();
    return $like ? true : false;
}

// Function to add like to post
function add_like(object $pdo, int $post_id, int $user_id) {
    $query = "INSERT INTO post_likes (post_id, user_id) VALUES (:post_id, :user_id)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
}

// Function to remove like from post
function remove_like(object $pdo, int $post_id, int $user_id) {
    $query = "DELETE FROM post_likes WHERE post_id = :post_id AND user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
}

// Function to get likes for post
function get_likes_for_post(object $pdo, int $post_id) {
    $query = "SELECT user_id FROM post_likes WHERE post_id = :post_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();
    $likes = $stmt->fetchAll();
    return $likes;
}



// POST EDIT FUNCTIONS

// Function to check if user is owner of post
function is_post_owner(object $pdo, int $post_id, int $user_id): bool {
    $query = "SELECT post_id FROM posts WHERE post_id = :post_id AND user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $post = $stmt->fetch();
    return $post ? true : false;
}

// Function to edit post
function edit_post(object $pdo, int $post_id, string $description) {
    $query = "UPDATE posts SET description = :description WHERE post_id = :post_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();
}



// POST COMMENT FUNCTIONS

// Function to create comment
function create_comment(object $pdo, int $user_id, int $post_id, string $comment) {
    $query = "INSERT INTO post_comments (user_id, post_id, comment) VALUES (:user_id, :post_id, :comment)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->bindParam(':comment', $comment);
    $stmt->execute();
}

// Function to check if comment exists
function comment_exists(object $pdo, int $comment_id): bool {
    $query = "SELECT comment_id FROM post_comments WHERE comment_id = :comment_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':comment_id', $comment_id);
    $stmt->execute();
    $comment = $stmt->fetch();
    return $comment ? true : false;
}

// Function to check if user is owner of comment
function is_comment_owner(object $pdo, int $comment_id, int $user_id): bool {
    $query = "SELECT comment_id FROM post_comments WHERE comment_id = :comment_id AND user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':comment_id', $comment_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $comment = $stmt->fetch();
    return $comment ? true : false;
}

// Function to delete comment
function delete_comment(object $pdo, int $comment_id) {
    $query = "DELETE FROM post_comments WHERE comment_id = :comment_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':comment_id', $comment_id);
    $stmt->execute();
}

// Get comments for post
function get_comments_for_post(object $pdo, int $post_id) {
    $query = "SELECT c.comment_id, c.user_id, c.comment, c.date, u.username, u.user_img
              FROM post_comments c
              JOIN users u ON c.user_id = u.user_id
              WHERE c.post_id = :post_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();
    $comments = $stmt->fetchAll();
    return $comments;
}



// POST DELETE FUNCTIONS

// Function to delete post
function delete_post(object $pdo, int $post_id) {
    $query = "DELETE FROM posts WHERE post_id = :post_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();
}

// Function to delete post likes
function delete_post_comments(object $pdo, int $post_id) {
    $query = "DELETE FROM post_comments WHERE post_id = :post_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();
}

// Function to delete post likes
function delete_post_likes(object $pdo, int $post_id) {
    $query = "DELETE FROM post_likes WHERE post_id = :post_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();
}
