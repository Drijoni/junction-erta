<?php
include '../../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $priority = "High"; // Assuming priority is always high based on the form
    $deadline = "2022-01-01"; // Assuming static deadline based on the form

    // Handling the image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imgData = file_get_contents($_FILES['image']['tmp_name']);
        $imgType = $_FILES['image']['type'];

        $stmt = $conn->prepare("INSERT INTO departments (name, description, priority, deadline, img) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $description, $priority, $deadline, $imgData);
    } else {
        $stmt = $conn->prepare("INSERT INTO departments (name, description, priority, deadline) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $description, $priority, $deadline);
    }

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
