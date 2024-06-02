<?php

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $loginQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($loginQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['name'] . $user['surname'];
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $user['role'];
        
            // Debugging statement to check the role
            echo "Role: " . $_SESSION['role'];
            header("Location: dashboard/dashboard.php");

        } else {
            echo "Invalid password.";
        }
        
        } else {
            echo "Invalid email.";
        }
}

$conn->close();
?>
