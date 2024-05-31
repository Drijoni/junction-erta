<?php
session_start();

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $company_code = isset($_POST['company_code']) ? $_POST['company_code'] : null;

    $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($checkEmailQuery);
    if ($result->num_rows > 0) {
        echo 'Email already taken.';
        exit;
    }

    $insertUserQuery = "INSERT INTO users (name, surname, email, password, company_code) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertUserQuery);
    $stmt->bind_param("sssss", $name, $surname, $email, $password, $company_code);

    if ($stmt->execute() === true) {
        // Set up session and role after successful registration
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['email'] = $email;
        $_SESSION['role'] = determineRole($company_code); // Your role determination logic here
        
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
        echo 'Error: ' . $stmt->error;
    }
    $stmt->close();
}

$conn->close();

function determineRole($company_code) {
    // Check if the company code matches any known codes
    switch ($company_code) {
        case 'superadmin_code': // Replace 'superadmin_code' with your actual superadmin company code
            return 'superadmin';
        case 'admin_code': // Replace 'admin_code' with your actual admin company code
            return 'admin';
        case 'member_code': // Replace 'member_code' with your actual member company code
            return 'member';
        default:
            // If no matching code is found, default to client role
            return 'client';
    }
}

?>
