<?php
// Database connection


// Fetch users with role 2 and 3 from the database
$usersQuery = "SELECT * FROM users WHERE role IN ('2', '3')";
$usersResult = $conn->query($usersQuery);

if (!$usersResult) {
    die("Error fetching users: " . $conn->error);
}

// Handle AJAX requests for updating and deleting users
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'update') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $surname = $_POST['surname'];

        $updateQuery = "UPDATE users SET name='$name', surname='$surname' WHERE id=$id";
        $updateResult = $conn->query($updateQuery);

        if (!$updateResult) {
            echo json_encode(["status" => "error", "message" => "Error updating user: " . $conn->error]);
            exit();
        } else {
            echo json_encode(["status" => "success", "message" => "User updated successfully"]);
            exit();
        }
    }

    if ($_POST['action'] == 'delete') {
        $id = $_POST['id'];

        $deleteQuery = "DELETE FROM users WHERE id=$id";
        $deleteResult = $conn->query($deleteQuery);

        if (!$deleteResult) {
            echo json_encode(["status" => "error", "message" => "Error deleting user: " . $conn->error]);
            exit();
        } else {
            echo json_encode(["status" => "success", "message" => "User deleted successfully"]);
            exit();
        }
    }
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to handle form submission via AJAX
            function submitForm(form) {
                var formData = form.serialize(); // Serialize form data
                $.ajax({
                    type: 'POST',
                    url: '',
                    data: formData,
                    success: function(response) {
                        var result = JSON.parse(response);
                        if (result.status === 'success') {
                            alert(result.message);
                            if (form.find('input[name="action"]').val() === 'delete') {
                                form.closest('tr').remove(); // Remove the row from the table
                            }
                        } else {
                            alert(result.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred. Please try again.');
                    }
                });
            }

            // Handle form submission for updating and deleting users
            $('form').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission
                submitForm($(this));
            });

            // Handle click event for delete buttons
            $('.delete-button').on('click', function(e) {
                e.preventDefault(); // Prevent the default button click behavior
                var form = $(this).closest('form');
                form.find('input[name="action"]').val('delete'); // Set the action to delete
                submitForm(form);
            });
        });

        $(document).ready(function() {
    // Existing code...

    // Export Monthly Report button click event
    $(".export-report").click(function() {
        $.ajax({
            type: 'POST',
            url: './content/export-report.php', // Adjust as needed
            data: { export: true },
            xhrFields: {
                responseType: 'blob'  // Important for handling the binary CSV file download
            },
            success: function(data) {
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(data);
                a.href = url;
                a.download = 'monthly_report.csv';
                document.body.append(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            }
        });
    });
});
    </script>

</head>
<body>
<div class="container">

    <div class="flex flex-col">
        <div class="flex flex-row w-full h-12 bg-white rounded-md items-center justify-between px-4 mt-4">
            <span class="font-bold">All Users</span>
            <div class="flex flex-row gap-2">
                <button style="float:left;text-decoration:none;" class="px-2 py-1.5 bg-cyan-500 rounded-md text-white export-report">Export Monthly Report</button>
                <button style="float:left;text-decoration:none;" class="px-2 py-1.5 bg-cyan-500 rounded-md text-white">Create new user</button>

            </div>

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
                        <input type="hidden" name="action" value="update">
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
                            <button type="submit">Save Changes</button>
                            <button type="button" class="delete-button">Delete</button>
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
