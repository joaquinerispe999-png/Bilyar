<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Bilyaran ni Joaqs</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/frontend/css/interface_style.css">

</head>

<body>

    <img src="assets/src/Bilyar_BG.png" alt="Background" class="bg-image">

    <div class="overlay"></div>

    <nav class="navbar">

        <div class="logo">
            <i class="fas fa-cue"></i>
            Bilyaran Ni Joaqs
        </div>

        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="login.php" class="active">Login</a></li>
            <li><a href="register.php">Sign Up</a></li>
        </ul>

    </nav>

    <div class="container">

        <div class="glass-card login-card">

            <div class="login-icon">
                <i class="fas fa-user-lock"></i>
            </div>

            <h1>Sign In</h1>
            <?php

            if(isset($_GET['success']))
            {
                echo "
                <p style='color:lightgreen;'>
                    Account created successfully.
                </p>
                ";
            }

            if(isset($_GET['error']))
            {
                echo "
                <p style='color:red;'>
                    Invalid Username or Password.
                </p>
                ";
            }
            ?>

            <p class="subtitle">
                Enter your credentials to continue
            </p>

            <form action="assets/process/login_process.php" method="POST">

                <div class="input-group">
                    <label>
                        <i class="fas fa-user"></i>
                        Username
                    </label>

                    <input type="text" name="username" required>
                </div>

                <div class="input-group">

                    <label>
                        <i class="fas fa-lock"></i>
                        Password
                    </label>

                    <input type="password" name="password" required>

                </div>

                <button type="submit" class="btn login-btn">

                    <i class="fas fa-right-to-bracket"></i>

                    Sign In

                </button>

                <div class="signup-section">

                    <p>Don't have an account yet?</p>

                    <a href="register.php" class="btn signup-btn">

                        Create Account

                    </a>

                </div>

                <?php if (isset($_GET['error'])): ?>

                    <div class="error-message">

                        Invalid Username or Password

                    </div>

                <?php endif; ?>

            </form>

        </div>

    </div>

</body>

</html>