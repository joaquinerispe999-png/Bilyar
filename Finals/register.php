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

    <link rel="stylesheet" href="assets/frontend/css/index_style.css">
    <link rel="stylesheet" href="assets/frontend/css/register_style.css">

</head>

<body>

    <img src="assets/src/Bilyar_BG.png" class="bg-image">

    <div class="overlay"></div>

    <nav class="navbar">

        <div class="logo">
            Bilyaran Ni Joaqs
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

            <form id="registerForm" action="process/signup_process.php" method="POST">

                <div class="register-layout">

                    <!-- STEP 1 -->
                    <div class="form-step active" id="step1">

                        <div class="input-group">
                            <label>Full Name</label>
                            <input type="text" name="fullname" required>
                        </div>

                        <div class="input-group">
                            <label>Username</label>
                            <input type="text" name="username" required>
                        </div>
                        <div id="usernameMessage" class="error-message"></div>
                        <button
                            type="button"
                            class="btn login-btn"
                            onclick="nextStep(1)"
                        >
                            Next
                        </button>

                    </div>

                    <!-- STEP 2 -->
                    <div class="form-step" id="step2">

                        <div class="input-group">
                            <label>Email</label>
                            <input type="email" name="email" required>
                        </div>
                        <div id="emailMessage" class="error-message"></div>
                        <div class="button-row">

                            <button
                                type="button"
                                class="btn signup-btn"
                                onclick="prevStep(2)"
                            >
                                Back
                            </button>

                            <button
                                type="button"
                                class="btn login-btn"
                                onclick="nextStep(2)"
                            >
                                Next
                            </button>

                        </div>

                    </div>

                    <!-- STEP 3 -->
                    <div class="form-step" id="step3">

                        <div class="input-group">
                            <label>Password</label>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                            >
                        </div>

                        <div class="input-group">
                            <label>Confirm Password</label>

                            <input
                                type="password"
                                name="confirm_password"
                                required
                            >
                        </div>

                        <div
                            id="passwordChecker"
                            class="password-checker"
                        >

                            <div
                                id="strengthText"
                                class="strength-label"
                            >
                            😭 Weak Password
                            </div>

                            <div class="progress-bar-container">
                                <div
                                    id="strengthBar"
                                    class="progress-bar-fill"
                                ></div>
                            </div>

                            <ul class="password-rules">

                                <li
                                    id="ruleLength"
                                    data-text="At least 8 characters"
                                    class="invalid"
                                >
                                    ✗ At least 8 characters
                                </li>

                                <li
                                    id="ruleUpper"
                                    data-text="Uppercase letter"
                                    class="invalid"
                                >
                                    ✗ Uppercase letter
                                </li>

                                <li
                                    id="ruleLower"
                                    data-text="Lowercase letter"
                                    class="invalid"
                                >
                                    ✗ Lowercase letter
                                </li>

                                <li
                                    id="ruleNumber"
                                    data-text="Number"
                                    class="invalid"
                                >
                                    ✗ Number
                                </li>

                                <li
                                    id="ruleSpecial"
                                    data-text="Special character"
                                    class="invalid"
                                >
                                    ✗ Special character
                                </li>

                            </ul>

                        </div>

                        <div class="button-row">

                            <button
                                type="button"
                                class="btn signup-btn"
                                onclick="prevStep(3)"
                            >
                                Back
                            </button>

                            <button
                                type="submit"
                                class="btn login-btn"
                            >
                                Create Account
                            </button>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>
    <script src="assets/frontend/js/register_script.js"></script>
</body>

</html>