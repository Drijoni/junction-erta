<?php
include '../../config.php';
header('Content-Type: application/json');

$task_id = $_POST['task_id'] ?? null;  // Use null coalescing operator to handle cases where task_id is not set

if ($task_id === null) {
    echo json_encode(['error' => 'No task ID provided']);
    exit;
}

$sql = "SELECT users.name, users.surname 
        FROM user_task_relation
        INNER JOIN users ON user_task_relation.user_id = users.id 
        WHERE user_task_relation.task_id = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $task_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $taskMembers = [];
        while ($row = $result->fetch_assoc()) {
            $taskMembers[] = $row;
        }
        echo json_encode($taskMembers);
    } else {
        echo json_encode(['error' => 'Query failed: ' . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['error' => 'Failed to prepare statement: ' . $conn->error]);
}

$conn->close();
?>
