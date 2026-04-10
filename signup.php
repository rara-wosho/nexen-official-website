<!-- FOR INSERT ADMIN ACCOUNT; NOT INCLUDED FOR DISPLAY -->

<?php
session_start(); // Start the session
include "db_connect.php"; // Include database connection

$message = ""; // Variable to store messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if all fields are filled
    if (empty($username) || empty($password)) {
        $message = "All fields are required.";
    } else {
        // Check if user already exists
        $stmt = $connection->prepare("SELECT * FROM admin");
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            // Hash the password and insert new user into the database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $connection->prepare("INSERT INTO admin (username, password) VALUES (:username, password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->execute();

            // Redirect to login.php after successful signup
            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEXEN - Signup</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            width: 300px;
        }
        input {
            margin: 10px 0;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .button {
            padding: 10px;
            margin: 10px 0;
            font-size: 1em;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Signup</h1>
    <p>Admin Access Only!</p>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" class="button">Signup</button>
    </form>
    <?php if ($message): ?>
        <p class="error"><?php echo $message; ?></p>
    <?php endif; ?>
    <button class="button" style="background-color: gray; padding-left: 130px; padding-right: 130px;" onclick="window.history.back()">Back</button>
</body>
</html>
