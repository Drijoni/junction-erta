<?php
session_start();

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $company_code = isset($_POST['company_code']) ? $_POST['company_code'] : null;

    // Check if company code is provided
    if (empty($company_code)) {
        $role = 'client';
    } else {
        // Assign role based on company code
        switch ($company_code) {
            case '1111':
                $role = 'superadmin';
                break;
            case '2222':
                $role = 'admin';
                break;
            case '3333':
                $role = 'member';
                break;
            default:
                // If company code is not recognized, default to client role
                $role = 'client';
                break;
        }
    }

    $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo 'Email already taken.';
        exit;
    }

    $insertUserQuery = "INSERT INTO users (name, surname, email, password, role) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertUserQuery);
    $stmt->bind_param("sssss", $name, $surname, $email, $password, $role);

    if ($stmt->execute()) {
        // Set up session and role after successful registration
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $role;
        
        // Redirect to dashboard based on the determined role
        switch ($role) {
            case 'superadmin':
                header("Location: dashboard/dashboard.php");
                break;
            case 'admin':
                header("Location: admin_dashboard.php");
                break;
            case 'member':
                header("Location: member_dashboard.php");
                break;
            case 'client':
                header("Location: client_dashboard.php");
                break;
        }
        exit();
    } else {
        echo 'Error: ' . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>
