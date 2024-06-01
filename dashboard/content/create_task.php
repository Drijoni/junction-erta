<?php
include '../../config.php';

$project_id = isset($_POST['project_id']) ? intval($_POST['project_id']) : 0;
$boardlist_id = isset($_POST['boardlist_id']) ? intval($_POST['boardlist_id']) : 0;
$task_name = isset($_POST['task_name']) ? $_POST['task_name'] : '';
$list_position = isset($_POST['list_position']) ? intval($_POST['list_position']) : 0;

if ($project_id > 0 && $boardlist_id > 0 && !empty($task_name)) {
    // Insert the new task into the tasks table
    $query = "INSERT INTO tasks (name) VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $task_name);
    $stmt->execute();
    $task_id = $stmt->insert_id;
    $stmt->close();

    // Insert the relation into the board_task_relation table
    $query = "INSERT INTO board_task_relation (project_id, boardlist_id, task_id, list_position) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiii", $project_id, $boardlist_id, $task_id, $list_position);
    $stmt->execute();
    $stmt->close();

    header("Location: ../dashboard.php?taskboard=$project_id");
} else {
    header("Location: ../dashboard.php?taskboard=$project_id");
}

$conn->close();
?>
