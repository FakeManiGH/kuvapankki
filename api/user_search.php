<?php

include '../includes/config_session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && is_logged_in()) {
    if (isset($_POST['search_user'])) {
        $user_id = $_SESSION['user_id'];
        $search = htmlspecialchars($_POST['search_user']);
        $search = "%$search%";

        include '../includes/connections.php';
        include '../includes/user_model.php';

        // Search users from database
        $users = search_users($pdo, $search, $user_id);

        $pdo = null;

        $data = array(
            'success' => true,
            'users' => $users
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