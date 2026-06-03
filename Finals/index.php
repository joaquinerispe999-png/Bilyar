<?php
session_start();

if(isset($_SESSION['user_id'])){
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bilyaran ni Joaqs</title>

    <link rel="stylesheet" href="assets/frontend/css/index_style.css">
</head>

<body>

    <img src="assets/src/Bilyar_BG.png" alt="Background" class="bg-image">

    <div class="overlay"></div>

    <nav class="navbar">
        <div class="logo">Bilyaran ni Joaqs</div>
    </nav>

    <div class="container">

        <div class="glass-card">

            <h1>Welcome</h1>

            <p class="subtitle">
                Experience a clean and modern billiard scheduller.
            </p>

            <div class="button-group">

                <a href="login.php" class="btn login-btn">
                    Log In
                </a>

                <div class="signup-section">

                    <p>Don't have an account yet?</p>

                    <a href="register.php" class="btn signup-btn">
                        Sign Up
                    </a>

                </div>

            </div>

        </div>

    </div>

</body>
</html>