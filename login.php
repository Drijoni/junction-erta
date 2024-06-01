<?php
session_start();

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $company_code = isset($_POST['company_code']) ? $_POST['company_code'] : null;

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

            if (!empty($company_code)) {
                // Check if company_code is valid and matches user's role or assign specific role
                // Implement your logic to validate company_code and assign roles here
                // For now, let's assume that the company_code directly assigns the role
                switch ($company_code) {
                    case 'superadmin_code': // replace with actual code
                        $_SESSION['role'] = 'superadmin';
                        header("Location: superadmin_dashboard.php");
                        break;
                    case 'admin_code': // replace with actual code
                        $_SESSION['role'] = 'admin';
                        header("Location: admin_dashboard.php");
                        break;
                    case 'member_code': // replace with actual code
                        $_SESSION['role'] = 'member';
                        header("Location: member_dashboard.php");
                        break;
                    default:
                        // If company code is not recognized, default to client role
                        $_SESSION['role'] = 'client';
                        header("Location: client_dashboard.php");
                        break;
                }
            } else {
                // If no company code is provided, default to client role
                $_SESSION['role'] = 'client';
                header("Location: client_dashboard.php");
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
