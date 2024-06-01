<?php
session_start();

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];
    $priority = $_POST['priority'];
    $img = $_FILES['img']['name'];
    $img_tmp = $_FILES['img']['tmp_name'];

    // Validate and move the uploaded image
    if (move_uploaded_file($img_tmp, "uploads/" . $img)) {
        $img_path = "uploads/" . $img;

        $insertProjectQuery = "INSERT INTO projects (name, description, deadline, priority, img) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertProjectQuery);
        $stmt->bind_param("sssss", $name, $description, $deadline, $priority, $img_path);

        if ($stmt->execute()) {
            echo "Project saved successfully.";
            // Redirect to projects list or another page
            header("Location: projects.php");
            exit();
        } else {
            echo 'Error: ' . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Failed to upload image.";
    }
}

$conn->close();
?>
