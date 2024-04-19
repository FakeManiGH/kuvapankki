<?php

include '../includes/config_session.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_logged_in()) {
    if (isset($data['post_id'])) {
        $user_id = intval($_SESSION['user_id']);
        $post_id = intval($data['post_id']);
        $gallery_id = intval($data['gallery_id']);
        $delete_images = intval($data['delete_images']);

        include '../includes/connections.php';
        include '../includes/posts_model.php';
        include '../includes/posts_ctrl.php';
        include '../includes/images_model.php';
        require_once '../includes/gallery_model.php';

        try {
            $errors = array();

            // Check if post exists
            if (!post_exists($pdo, $post_id)) {
                $errors[] = 'Julkaisua ei löydy.';
            }

            // Check if user is post owner
            if (!is_post_owner($pdo, $post_id, $user_id) && !is_gallery_admin($pdo, $user_id, $gallery_id)) {
                $errors[] = 'Sinulla ei ole oikeutta poistaa tätä julkaisua.';
            }

            // if no errors, delete post
            if (empty($errors)) {

                // DELETING POST
                delete_post($pdo, $post_id);

                // DELETING POST COMMENTS
                delete_post_comments($pdo, $post_id);

                // DELETING POST LIKES
                delete_post_likes($pdo, $post_id);

                if ($delete_images === 1) {

                    // GETTING IMAGES
                    $images = sanitize_array($data['images']);

                    // DELETING IMAGES
                    foreach ($images as $image) {
                        $image_id = intval($image['image_id']);
                        $file_name = $image['file_name'];
                        $url = $image['url'];

                        // Delete image from database
                        delete_image($pdo, $image_id);

                        // Delete image file
                        if (file_exists('../../galleries/'. $url)) {
                            unlink('../../galleries/'. $url);
                        }
                    }
                }

                $data = array(
                    'success' => true,
                    'message' => 'Julkaisu ja kuvat poistettu.'
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
            error_log("Database error: " . $e->getMessage());
            $data = array(
                'success' => false,
                'error' => 'Tietokantavirhe, yritä uudelleen.'
            );
            header('Content-Type: application/json');
            echo json_encode($data);
            $pdo = null;
            exit();
        }

    } else {
        $data = array(
            'success' => false,
            'error' => 'Julkaisua ei löydy.'
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