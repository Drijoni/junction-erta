<?php
include '../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $project_id = $_POST['project_id'];
    $list_name = $_POST['list_name'];
    $list_position = $_POST['list_position'];

    $sql = "INSERT INTO board_list (project_id, list_name, list_position) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("isi", $project_id, $list_name, $list_position);

    if ($stmt->execute()) {
        header("Location: ../dashboard.php?taskboard=$project_id");
    } else {
        $error = $stmt->error;
        header("Location: dashboard.php?taskboard=$project_id");
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>
