<?php

require_once '../config/db.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] != 'POST')
{
    header("C:\Users\ACER\OneDrive - National University\Desktop\Gdot\Finals\login.php");
    exit();
}

$username = trim($_POST['username']);
$password = trim($_POST['password']);

if(
    empty($username) ||
    empty($password)
)
{
    header("Location: ../login.php?error=empty");
    exit();
}

$stmt = mysqli_prepare(
    $conn,
    "SELECT
        user_id,
        fullname,
        username,
        email,
        password,
        role
    FROM users
    WHERE username = ?
    LIMIT 1"
);

mysqli_stmt_bind_param(
    $stmt,
    "s",
    $username
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) == 1)
{
    $user = mysqli_fetch_assoc($result);

    if(
        password_verify(
            $password,
            $user['password']
        )
    )
    {

        $_SESSION['user_id'] = $user['user_id'];

        $_SESSION['fullname'] = $user['fullname'];

        $_SESSION['username'] = $user['username'];

        $_SESSION['email'] = $user['email'];

        $_SESSION['role'] = $user['role'];

        header("Location: ../dashboard.php");
        exit();
    }
}

header("Location: ../login.php?error=invalid");
exit();

?>