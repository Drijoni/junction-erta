<?php
include '../../config.php';


$response = ['success' => false]; // Initialize response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = $_POST['project_id'] ?? '';
    $list_name = $_POST['list_name'] ?? '';
    $list_position = $_POST['list_position'] ?? '';

    if (empty($project_id) || empty($list_name) || empty($list_position)) {
        $response['error'] = 'Missing required fields';
        echo json_encode($response);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO board_list (project_id, list_name, list_position) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("isi", $project_id, $list_name, $list_position);

        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['error'] = $stmt->error;
        }
        $stmt->close();
    } else {
        $response['error'] = $conn->error;
    }
} else {
    $response['error'] = 'Invalid request method';
}

echo json_encode($response);
$conn->close();
?>
