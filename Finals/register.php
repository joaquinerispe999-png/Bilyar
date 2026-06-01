<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register - Bilyaran ni Joaqs</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/frontend/css/interface_style.css">

</head>

<body>

    <img src="assets/src/Bilyar_BG.png" class="bg-image">

    <div class="overlay"></div>

    <nav class="navbar">

        <div class="logo">
            BilliardPro
        </div>

        <ul class="nav-links">

            <li><a href="index.php">Home</a></li>

            <li><a href="login.php">Login</a></li>

            <li><a href="register.php">Register</a></li>

        </ul>

    </nav>

    <div class="container">

        <div class="glass-card login-card">

            <h1>Create Account</h1>
            <?php

            if(isset($_GET['error']))
            {
                echo "<p style='color:red;'>";

                switch($_GET['error'])
                {
                    case 'empty':
                        echo "All fields are required.";
                        break;
                    case 'password':
                        echo "Passwords do not match.";
                        break;
                    case 'exists':
                        echo "Username or Email already exists.";
                        break;
                    case 'failed':
                        echo "Registration failed.";
                        break;
                }
                echo "</p>";
            }
            ?>
            
            <p class="subtitle">

                Register a new account

            </p>

            <form action="process/signup_process.php" method="POST">

                <div class="input-group">
                    <label>Full Name</label>
                    <input type="text" name="fullname" required>
                </div>

                <div class="input-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>

                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <div class="input-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required>
                </div>

                <button type="submit" class="btn login-btn">

                    Create Account

                </button>

            </form>

        </div>

    </div>

</body>

</html>