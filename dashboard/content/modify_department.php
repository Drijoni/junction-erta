<?php
include '../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve data from the form
    $id = $_POST['modalID'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $priority = $_POST['departmenType'];
    // File handling for image
    if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];
        $upload_dir = 'uploads/';
        move_uploaded_file($image_tmp_name, $upload_dir . $image_name);
        $image = $upload_dir . $image_name;
    } else {
        $image = null;
    }

    // Create the SQL update statement
    $query = "UPDATE departments SET 
              name = ?, 
              description = ?, 
              priority = ?";

    if ($image) {
        $query .= ", img = ?";
    }
    
    $query .= " WHERE id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($query);

    if ($image) {
        $stmt->bind_param('ssssi', $name, $description, $priority, $image, $id);
    } else {
        $stmt->bind_param('sssi', $name, $description, $priority, $id);
    }

    // Execute the statement
    if ($stmt->execute()) {
        echo "Department updated successfully.";
    } else {
        echo "Error updating department: " . $conn->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Redirect back to the main page
    header("Location: /path/to/your/page.php"); // Adjust the path as needed
    exit();
} else {
    echo "Invalid request method.";
}
?>
