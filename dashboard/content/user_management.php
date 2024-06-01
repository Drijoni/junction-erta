<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'junction-erta';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Fetch users with role 2 and 3 from the database
$usersQuery = "SELECT * FROM users WHERE role IN ('2', '3')";
$usersResult = $conn->query($usersQuery);

// Check if the query execution was successful
if (!$usersResult) {
    die("Error fetching users: " . $conn->error);
}

// Handle form submission for updating name and surname
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];

        $updateQuery = "UPDATE users SET name='$name', surname='$surname' WHERE id=$id";
        $updateResult = $conn->query($updateQuery);

        if (!$updateResult) {
            die("Error updating user: " . $conn->error);
        }
    }

    // Handle form submission for deleting user
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];

        $deleteQuery = "DELETE FROM users WHERE id=$id";
        $deleteResult = $conn->query($deleteQuery);

        if (!$deleteResult) {
            die("Error deleting user: " . $conn->error);
        }
    }

    // Redirect back to the same page after form submission
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .table-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background-color: #f3f4f6;
            font-weight: bold;
            color: #4b5563;
        }

        input[type="text"] {
            border: none;
            background-color: transparent;
            width: 100%;
            font-size: 14px;
            line-height: 1.5;
            color: #4b5563;
            outline: none;
        }

        button {
            border: none;
            background-color: transparent;
            cursor: pointer;
            font-size: 14px;
            line-height: 1.5;
            color: #4b5563;
        }

        button:hover {
            text-decoration: underline;
        }

        .action-buttons button:first-child {
            margin-right: 10px;
        }
    </style>
</head>
<body>
<div class="container">

    <div class="flex flex-col">
        <div class="flex flex-row w-full h-12 bg-white rounded-md items-center justify-between px-4 mt-4">
            <span class="font-bold">All Users</span>
        </div>

        <div class="flex flex-row gap-8 w-full h-36 bg-white rounded-md items-center p-4 mt-4">
            <!-- Total Users -->
            <div class="flex flex-col border border-slate-200 justify-center items-center w-48 h-full rounded-md">
                <span class="font-bold text-3xl text-start">
                    <?php echo $usersResult->num_rows; ?>
                </span>
                <span>total users</span>
            </div>
        </div>
    </div>

    <div class="table-container mt-4">
        <table>
            <thead>
            <tr>
                <th>Name</th>
                <th>Surname</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($user = $usersResult->fetch_assoc()) { ?>
                <tr>
                    <form method="post">
                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                        <td><input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>"></td>
                        <td><input type="text" name="surname" value="<?php echo htmlspecialchars($user['surname']); ?>"></td>
                        <td>
                            <?php
                            if ($user['role'] == '2') {
                                echo "Admin";
                            } elseif ($user['role'] == '3') {
                                echo "Member";
                            }
                            ?>
                        </td>
                        <td class="action-buttons">
                            <button type="submit" name="update">Save Changes</button>
                            <button type="submit" name="delete">Delete</button>
                        </td>
                    </form>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
