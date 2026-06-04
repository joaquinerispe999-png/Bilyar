<?php

require_once '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] != 'POST')
{
    echo json_encode([
        "result" => "Invalid request."
    ]);
    exit();
}

$fullname = trim($_POST['fullname']);
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);
$confirm_password = trim($_POST['confirm_password']);

if(
    empty($fullname) ||
    empty($username) ||
    empty($email) ||
    empty($password) ||
    empty($confirm_password)
)
{
    echo json_encode([
        "result" => "Please fill out all fields."
    ]);
    exit();
}

if($password !== $confirm_password)
{
    echo json_encode([
        "result" => "Passwords do not match."
    ]);
    exit();
}

$checkUser = mysqli_prepare(
    $conn,
    "SELECT user_id FROM users WHERE username=? OR email=?"
);

mysqli_stmt_bind_param(
    $checkUser,
    "ss",
    $username,
    $email
);

mysqli_stmt_execute($checkUser);

$result = mysqli_stmt_get_result($checkUser);

if(mysqli_num_rows($result) > 0)
{
    echo json_encode([
        "result" => "Username or Email already exists."
    ]);
    exit();
}

$hashedPassword =
    password_hash(
        $password,
        PASSWORD_DEFAULT
    );

$insert = mysqli_prepare(
    $conn,
    "INSERT INTO users
    (
        fullname,
        username,
        email,
        password
    )
    VALUES
    (
        ?,
        ?,
        ?,
        ?
    )"
);

mysqli_stmt_bind_param(
    $insert,
    "ssss",
    $fullname,
    $username,
    $email,
    $hashedPassword
);

if(mysqli_stmt_execute($insert))
{
    echo json_encode([
        "result" => "success"
    ]);
}
else
{
    echo json_encode([
        "result" => "Database error."
    ]);
}

exit();
?>