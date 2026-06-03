<?php

require_once '../config/db.php';

$type = $_POST['type'] ?? '';
$value = trim($_POST['value'] ?? '');

$response = [
    'exists' => false
];

if($type === 'username')
{
    $stmt = mysqli_prepare(
        $conn,
        "SELECT user_id FROM users WHERE username = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $value
    );

    mysqli_stmt_execute($stmt);

    mysqli_stmt_store_result($stmt);

    $response['exists'] =
        mysqli_stmt_num_rows($stmt) > 0;
}

if($type === 'email')
{
    $stmt = mysqli_prepare(
        $conn,
        "SELECT user_id FROM users WHERE email = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $value
    );

    mysqli_stmt_execute($stmt);

    mysqli_stmt_store_result($stmt);

    $response['exists'] =
        mysqli_stmt_num_rows($stmt) > 0;
}

header('Content-Type: application/json');

echo json_encode($response);