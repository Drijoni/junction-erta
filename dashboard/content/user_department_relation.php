<?php
include '../../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = intval($_POST['userID']);
    $department_id = intval($_POST['departmentID']);

    $stmt = $conn->prepare("INSERT INTO user_department_relations (user_id, department_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $department_id);

    if ($stmt->execute()) {
        header("Location: ../dashboard.php?departments");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}


?>