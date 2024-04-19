<?php

include '../includes/config_session.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_logged_in()) {
    if (isset($data['post_id'])) {

        include '../includes/connections.php';
        include '../includes/posts_model.php';

        $post_id = intval($data['post_id']);
        $user_id = $_SESSION['user_id'];

        try {
            // Check if post exists
            if (!post_exists($pdo, $post_id) || empty($post_id)) {
                $data = array(
                    'success' => false,
                    'error' => 'Julkaisua ei löydy.'
                );
                header('Content-Type: application/json');
                echo json_encode($data);
                $pdo = null;
                exit();

            } else {
                // Check if like exists
                if (like_exists($pdo, $post_id, $user_id)) {
                    // Unlike the post
                    remove_like($pdo, $post_id, $user_id);
                    $liked = false;
                } else {
                    // Like the post
                    add_like($pdo, $post_id, $user_id);
                    $liked = true;
                }

                $likes = get_likes_for_post($pdo, $post_id);
                $data = array(
                    'success' => true,
                    'liked' => $liked,
                    'likes' => $likes
                );
                header('Content-Type: application/json');
                echo json_encode($data);
                $pdo = null;
                exit();
            }
            
        } catch (PDOException $e) {
            error_log($e->getMessage(), 0);
            $data = array(
                'success' => false,
                'error' => 'Tietokantaongelma. Yritä myöhemmin uudelleen.'
            );
            header('Content-Type: application/json');
            echo json_encode($data);
            $pdo = null;
            exit();
        }

    } else {
        $data = array(
            'success' => false,
            'error' => 'Julkaisun tunniste puuttuu.'
        );
        header('Content-Type: application/json');
        echo json_encode($data);
        $pdo = null;
        exit();
    }
    
} else {

    $_SESSION['404_error'] = "Sivua ei löytynyt tai sinulla ei ole siihen oikeutta.";
    header('Location: ../404.php?virhe');
    die();
}