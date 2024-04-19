<?php

include '../includes/config_session.php';

if (is_logged_in()) {
    include '../includes/connections.php';
    include '../includes/posts_model.php';
    include '../includes/posts_ctrl.php';

    $user_id = $_SESSION['user_id'];

    try {
        // Get galleries where user is member
        $galleries = get_galleries_where_member($pdo, $user_id);

        // Get posts from galleries where user is member
        $posts = get_posts_from_galleries($pdo, $galleries);

        // Get post likes and check if user has liked posts
        $posts = get_post_likes_and_user_likes($pdo, $posts, $user_id);

        // Get images for posts
        $posts = get_images_for_posts($pdo, $posts);

        // get comments for posts
        $posts = get_comments_for_posts($pdo, $posts);

        // Sanitize post data
        $posts = sanitize_array($posts);

        $data = array(
            'success' => true,
            'posts' => $posts,
            'appUserId' => $user_id
        );
        header('Content-Type: application/json');
        echo json_encode($data);
        $pdo = null;
        exit();

    } catch (PDOException $e) {
        error_log($e->getMessage(), 0);
        $data = array(
            'success' => false,
            'errors' => 'Tietokantaongelma'. $e .'. Yritä myöhemmin uudelleen.'
        );
        header('Content-Type: application/json');
        echo json_encode($data);
        $pdo = null;
        exit();
    }
    
} else {
    $data = array(
        'success' => false,
        'errors' => 'Kirjaudu sisään nähdäksesi julkaisuja.'
    );
    header('Content-Type: application/json');
    echo json_encode($data);
    $pdo = null;
    exit();
}