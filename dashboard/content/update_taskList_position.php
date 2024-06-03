<?php
include '../../config.php';

if (isset($_POST['task_id']) && isset($_POST['new_list_id'])) {
    $task_id = intval($_POST['task_id']);
    $new_boardlist_id = intval($_POST['new_list_id']);

    $query = "UPDATE board_task_relation SET boardlist_id = ? WHERE task_id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("ii", $new_boardlist_id, $task_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Task updated successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No changes made to the task.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid task or list ID.']);
}

$conn->close();
?>
