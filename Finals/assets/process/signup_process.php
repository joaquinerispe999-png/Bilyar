<?php

require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST')
{
    header("Location: ../register.php");
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
    header("Location: ../register.php?error=empty");
    exit();
}

if($password !== $confirm_password)
{
    header("Location: ../register.php?error=password");
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
    header("Location: ../register.php?error=exists");
    exit();
}

$hashedPassword = password_hash(
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
    header("Location: ../login.php?success=registered");
    exit();
}
else
{
    header("Location: ../register.php?error=failed");
    exit();
}
?>