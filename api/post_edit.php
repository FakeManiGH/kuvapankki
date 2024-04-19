<?php

include '../includes/config_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_logged_in()) {
    if (isset($_POST['post_id'])) {
        $user_id = intval($_SESSION['user_id']);
        $post_id = intval($_POST['post_id']);
        $description = htmlspecialchars($_POST['edit_desc']);

        include '../includes/connections.php';
        include '../includes/posts_model.php';

        try {
            $errors = array();

            if (!post_exists($pdo, $post_id)) {
                $errors[] = 'Julkaisua ei löydy.';
            }

            if (empty($description)) {
                $errors[] = 'Kuvaus ei saa olla tyhjä.';
            }

            if (strlen($description) > 400) {
                $errors[] = 'Kuvauksen maksimipituus on 400 merkkiä.';
            }

            if (is_post_owner($pdo, $post_id, $user_id) === false) {
                $errors[] = 'Sinulla ei ole oikeutta muokata tätä julkaisua.';
            }

            if (empty($errors)) {
                edit_post($pdo, $post_id, $description);
                $data = array(
                    'success' => true,
                    'message' => 'Julkaisun muokkaus onnistui.'
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

    } else {
        $data = array(
            'success' => false,
            'error' => 'Julkaisua ei löydy.'
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