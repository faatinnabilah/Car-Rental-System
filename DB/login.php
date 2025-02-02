<?php
include 'home.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    //Input sanitization, remove whitespace
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $psswd = trim($_POST['psswd']);

    //Input validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='message'>Invalid email format.</div>";
        exit;
    }

    //Use prepared statement
    $sql = "SELECT * FROM Users WHERE email = ?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // password verify
        if (password_verify($psswd, $user['psswd'])) {
            session_start();
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            echo "<div class='message'>Login successful. Redirecting to homepage...</div>";
            header('Refresh: 2; URL=homepage.php'); 
        } else {
            echo "<div class='message'>Invalid email or password.</div>";
        }
    } else {
        echo "<div class='message'>Invalid email or password.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body>
<div class="container">
        <div class="box form-box">
            <header>Login</header>
            <form action="login.php" method="POST">
                <div class="field input">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="field input">
                    <label for="password">Password:</label>
                    <input type="password" id="psswd" name="psswd" required>
                </div>
                <button class="btn submit" type="submit" name="login">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>
