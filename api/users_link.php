<?php

include '../includes/config_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $names = $input['names'];

    require_once '../includes/connections.php';
    require_once '../includes/user_model.php';

    $users = [];

    try {
        foreach ($names as $name) {
            $user = get_user_by_name($pdo, $name);
            if ($user) {
                $users[] = $user;
            }
        }

        $data = array(
            'success' => 'Käyttäjät haettu.',
            'users' => $users
        );
        header('Content-Type: application/json');
        echo json_encode($data);
        $pdo = null;
        die();

    } catch (PDOException $e) {

        error_log("Database error: " . $e->getMessage());
        $data = array(
            'error' => 'Käyttäjien haku epäonnistui.'
        );
        header('Content-Type: application/json');
        echo json_encode($data);
        $pdo = null;
        die();
    }

    if ($users) {
        echo json_encode($users);
    } else {
        echo json_encode(['error' => 'Käyttäjiä ei löytynyt.']);
    }
    $pdo = null;

} else {

    $_SESSION['404_error'] = "Sivua ei löytynyt tai sinulla ei ole siihen oikeutta.";
    header('Location: ../404.php?virhe');
    die();
}