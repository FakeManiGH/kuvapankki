<?php

include 'includes/connections.php';
include 'includes/friends_get_ctrl.php';
include 'includes/friends_get_model.php';

// Haetaan saapuneet kaveripyynnöt
$requests = get_incoming_requests($pdo, $_SESSION['user_id']);

// Haetaan lähetetyt kaveripyynnöt
$sent_requests = get_sent_requests($pdo, $_SESSION['user_id']);

// Suljetaan tietokantayhteys ja nollataan muuttujat
$stmt = null;
