<?php
include '../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];
    $priority = $_POST['priority'];
    $department_id = $_POST['department']; // Retrieve department ID from the form
    
    $img_path = null;
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $img = $_FILES['img']['name']; 
        $img_tmp = $_FILES['img']['tmp_name'];

        // Validate and move the uploaded image
        if (move_uploaded_file($img_tmp, "uploads/" . $img)) {
            $img_path = "uploads/" . $img;
        } else {
            echo "Failed to upload image.";
            exit();
        }
    }

    // Insert project into projects table
    if ($img_path) {
        $insertProjectQuery = "INSERT INTO projects (name, description, deadline, priority, img) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertProjectQuery);
        $stmt->bind_param("sssss", $name, $description, $deadline, $priority, $img_path);
    } else {
        $insertProjectQuery = "INSERT INTO projects (name, description, deadline, priority) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertProjectQuery);
        $stmt->bind_param("ssss", $name, $description, $deadline, $priority);
    }

    if ($stmt->execute()) {
        // Retrieve the ID of the newly inserted project
        $project_id = $stmt->insert_id;

        // Insert into project_department_relations table
        $insertRelationQuery = "INSERT INTO project_department_relations (project_id, department_id) VALUES (?, ?)";
        $stmtRelation = $conn->prepare($insertRelationQuery);
        $stmtRelation->bind_param("ii", $project_id, $department_id);

        if ($stmtRelation->execute()) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            echo 'Error: ' . $stmtRelation->error;
        }

        $stmtRelation->close();
    } else {
        echo 'Error: ' . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
