<?php
include '../../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = intval($_POST['userID']);
    $task_id = intval($_POST['taskID']);
    $project_id = intval($_POST['hiddenProjectID']);

    $stmt = $conn->prepare("INSERT INTO user_task_relation (user_id, task_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $task_id);

    if ($stmt->execute()) {
        header("Location: ../dashboard.php?taskboard=$project_id");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}


?>