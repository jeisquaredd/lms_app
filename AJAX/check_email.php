<?php
require_once '../classes/database.php';

header('Content-Type: application/json'); // Ensure JSON response

if (isset($_POST['email'])) {
    $email = $_POST['email']; 
    $con = new database();

    $db = $con->opencon();
    if (!$db) {
        echo json_encode(['error' => 'Database connection failed']);
        exit;
    }

    $query = $db->prepare("SELECT user_email FROM Users WHERE user_email = ?");
    $query->execute([$email]);
    $existingUser = $query->fetch();

    if ($existingUser) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }
}
?>
