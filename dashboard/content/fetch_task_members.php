<?php
include '../../config.php';
$department_id = $_POST['department_id'];

$sql = "SELECT users.id, users.name, users.surname 
        FROM user_department_relations 
        INNER JOIN users ON user_department_relations.user_id = users.id 
        WHERE user_department_relations.department_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $department_id);
$stmt->execute();
$result = $stmt->get_result();

$departmentMembers = [];
while ($row = $result->fetch_assoc()) {
    $departmentMembers[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($departmentMembers);
?>  