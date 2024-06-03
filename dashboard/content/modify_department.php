<?php
include '../../config.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve department ID from the form
    $id = $_POST['ID'];

    // Retrieve updated department information from the form
    $name = $_POST['name'];
    $description = $_POST['description'];
    $departmentType = $_POST['departmenType']; // Assuming this represents department type
    $deadline = $_POST['deadline'];

    // Update the department information in the database
    $updateQuery = "UPDATE departments SET name='$name', description='$description', deadline='$deadline' WHERE id=$id";

    if ($conn->query($updateQuery) === TRUE) {
        // If update is successful, redirect the user back to the page where they can see the updated information
        header("Location:../dashboard.php?departments");
        // exit();
    } else {
        // If there is an error with the update query, display an error message
        echo "Error updating department: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
