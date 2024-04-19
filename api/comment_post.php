<?php

include '../includes/config_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_logged_in()) {
    if (isset($_POST['post_id'])) {
        $user_id = intval($_SESSION['user_id']);
        $post_id = intval($_POST['post_id']);
        $comment = htmlspecialchars($_POST['comment']);

        include '../includes/connections.php';
        include '../includes/posts_model.php';
        require_once '../includes/regex.php';

        try {
            $errors = array();

            // Check if post exists
            if (!post_exists($pdo, $post_id)) {
                $errors[] = 'Julkaisua ei löydy.';
            }

            // Check if comment is empty
            if (empty($comment)) {
                $errors[] = 'Kommentti ei saa olla tyhjä.';
            }

            // Check if comment is too long
            if (strlen($comment) > 300) {
                $errors[] = 'Kommentin maksimipituus on 300 merkkiä.';
            }

            // if no errors, create comment
            if (empty($errors)) {

                create_comment($pdo, $user_id, $post_id, $comment);

                // Send notification if user is mentioned
                /* if (preg_match_all($patternLink, $comment, $matches)) {
                    $names = $matches[1];
                    $users = [];

                    foreach ($names as $name) {
                        $user = get_user_by_name($pdo, $name);
                        if ($user) {
                            $users[] = $user;
                        }
                    }

                    if ($users) {
                        $notification = $user_id . ' mentioned you in a comment.';
                        foreach ($users as $user) {
                            create_notification($pdo, $user['user_id'], $notification);
                        }
                    }
                } */

                // Get comments for post
                $comments = get_comments_for_post($pdo, $post_id);

                $data = array(
                    'success' => true,
                    'comments' => $comments
                );
                header('Content-Type: application/json');
                echo json_encode($data);
                $pdo = null;
                exit();

            } else {
                $data = array(
                    'success' => false,
                    'errors' => $errors
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
    }
} else {

    $_SESSION['404_error'] = "Sivua ei löytynyt tai sinulla ei ole siihen oikeutta.";
    header('Location: ../404.php?virhe');
    die();
}