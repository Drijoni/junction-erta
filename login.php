<?php
session_start();

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
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $user['role'];
        
            // Debugging statement to check the role
            echo "Role: " . $_SESSION['role'];
        
            // Redirect to dashboard based on the determined role
            switch ($_SESSION['role']) {
                case 'superadmin':
                    header("Location: superadmin_dashboard.php");
                    break;
                case 'admin':
                    header("Location: admin_dashboard.php");
                    break;
                case 'member':
                    header("Location: member_dashboard.php");
                    break;
                case 'client':
                default:
                    header("Location: client_dashboard.php");
                    break;
            }
            exit();
        } else {
            echo "Invalid password.";
        }
        
        } else {
            echo "Invalid email.";
        }
}

$conn->close();
?>
