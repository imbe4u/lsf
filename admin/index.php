<?php
session_start();

// 🔐 Check if already logged in
if (isset($_SESSION['username'])) {
    header("Location: admin.php");
    exit;
}

// 🧠 Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Replace this with DB check if needed
    if ($username == "admin" && $password == "admin123") {
        $_SESSION['username'] = $username;
        header("Location: admin.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <style>
    /* Same styles as before */
      <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    body {
      height: 100vh;
      background: linear-gradient(to right, #2980b9, #6dd5fa);
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-container {
      background-color: #fff;
      padding: 30px 40px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 350px;
    }

    .login-container h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
    }

    .login-container label {
      display: block;
      margin-bottom: 8px;
      color: #555;
      font-weight: bold;
    }

    .login-container input[type="text"],
    .login-container input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 6px;
      border: 1px solid #ccc;
    }

    .login-container button {
      width: 100%;
      padding: 12px;
      background-color: #2980b9;
      border: none;
      border-radius: 6px;
      color: white;
      font-size: 16px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .login-container button:hover {
      background-color: #2573a6;
    }

    .login-container p {
      text-align: center;
      margin-top: 15px;
      color: #666;
      font-size: 14px;
    }

  </style>
</head>
<body>
  <div class="login-container">
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>

      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
