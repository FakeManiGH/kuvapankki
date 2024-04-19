<?php

include '../includes/config_session.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_logged_in()) {
    if (isset($data['comment_id'])) {
        $user_id = intval($_SESSION['user_id']);
        $comment_id = intval($data['comment_id']);
        $post_id = intval($data['post_id']);
        
        include '../includes/connections.php';
        include '../includes/posts_model.php';

        try {
            $errors = array();

            // Check if comment exists
            if (!comment_exists($pdo, $comment_id)) {
                $errors[] = 'Kommenttia ei löydy.';
            }

            // Check if user is comment owner
            if (!is_comment_owner($pdo, $comment_id, $user_id)) {
                $errors[] = 'Sinulla ei ole oikeutta poistaa tätä kommenttia.';
            }

            // if no errors, delete comment
            if (empty($errors)) {

                // DELETING COMMENT
                delete_comment($pdo, $comment_id);

                // Get comments for post
                $comments = get_comments_for_post($pdo, $post_id);

                $data = array(
                    'success' => true,
                    'post_id' => $post_id,
                    'comments' => $comments,
                    'appUserId' => $user_id,
                );
                header('Content-Type: application/json');
                echo json_encode($data);
                exit();

            } else {
                $data = array(
                    'success' => false,
                    'error' => $errors
                );
                header('Content-Type: application/json');
                echo json_encode($data);
                exit();
            }

        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            $data = array(
                'success' => false,
                'error' => 'Tietokantavirhe, yritä uudelleen.'
            );
            header('Content-Type: application/json');
            echo json_encode($data);
            exit();
        }
        
    } else {
        $data = array(
            'success' => false,
            'error' => 'Kommenttia ei löydy.'
        );
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

} else {

    $_SESSION['404_error'] = "Sivua ei löytynyt tai sinulla ei ole siihen oikeutta.";
    header('Location: ../404.php?virhe');
    die();
}