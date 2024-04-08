<?php
// Alwyas include this file after session start

require 'includes/connections.php';
require 'includes/storage_model.php';

$user_id = $_SESSION['user_id'];

// Get user's images
$images = get_images($pdo, $user_id);

// Space used by user
$used_space = 0;

// Calculate space used by user
foreach ($images as $image) {
    $used_space += $image['size'];
}

// Covert bytes to appropriate unit (FOR VIEW)
if ($used_space < 1000000) {
    $used_space_view = round($used_space / 1000, 2) . ' Kt';
} else if ($used_space >= 1000000) {
    $used_space_view = round($used_space / 1000 / 1000, 2) . ' Mt';
} else if ($used_space >= 1000000000) {
    $used_space_view = round($used_space / 1000 / 1000 / 1000, 2) . ' Gt';
}



//get user's subscription
$subscription = get_subscription($pdo, $user_id);

// Set sub name based on subscription
if ($subscription['sub_type'] === 1) {
    $subscription['sub_name'] = 'Basic (ilmainen)';
} else if ($subscription['sub_type'] === 2) {
    $subscription['sub_name'] = 'Premium (maksullinen)';
} else if ($subscription['sub_type'] === 3) {
    $subscription['sub_name'] = 'Ultimate (maksullinen)';
}

// Set storage space based on subscription
if ($subscription['sub_type'] === 1) {
    $storage_space = 1000000000; // 1 GB
} else if ($subscription['sub_type'] === 2) {
    $storage_space = 5000000000; // 5 GB
} else if ($subscription['sub_type'] === 3) {
    $storage_space = 10000000000; // 10 GB
}

// Show storage space in gb (FOR VIEW)
$storage_space_view = round($storage_space / 1000 / 1000 / 1000, 2) . ' Gt';

// Caclulate percentage of used space (% of total storage space)
$used_space_percentage = round(($used_space / $storage_space) * 100, 2);