<?php
// Start the session


// Include the database configuration
include_once('config.php');

// Initialize variables to store user data
$name = '';
$surname = '';
$email = '';

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    // Get the user ID from the session
    $userId = $_SESSION['user_id'];

    // Fetch the user's data from the database
    $userQuery = "SELECT * FROM users WHERE id = $userId";
    $userResult = $conn->query($userQuery);

    // Check if the query executed successfully and fetched any data
    if ($userResult && $userResult->num_rows > 0) {
        // Fetch the user's data
        $userData = $userResult->fetch_assoc();
        $name = $userData['name'];
        $surname = $userData['surname'];
        $email = $userData['email'];
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $newName = $_POST['name'];
    $newSurname = $_POST['surname'];
    $newEmail = $_POST['email'];

    // Update the user's data in the database
    $updateQuery = "UPDATE users SET name = '$newName', surname = '$newSurname', email = '$newEmail' WHERE id = $userId";
    $updateResult = $conn->query($updateQuery);

    if ($updateResult) {
        // Update successful, redirect to the profile page or display a success message
        header("Location: editprofile.php"); // Change 'profile.php' to the appropriate profile page
        exit();
    } else {
        // Update failed, handle the error (e.g., display an error message)
        $errorMessage = "Failed to update user data.";
    }
}


// Assuming you have the user's role stored in $_SESSION['role']
if(isset($_SESSION['role'])) {
    // Set the href based on the user's role
    if($_SESSION['role'] == 1) {
        $href = "dashboard/dashboard.php";
    } elseif($_SESSION['role'] == 2) {
        $href = "dashboard/admindashboard.php";
    } elseif($_SESSION['role'] == 3) {
        $href = "dashboard/member-dashboard.php";
    } else {
        // Handle other roles if needed
        $href = "#";
    }
} else {
    // Handle the case when role is not set in the session
    $href = "#";
}

?>

<script src="https://cdn.tailwindcss.com"></script>

<div class="container mx-auto p-4">
    <h1 class="text-primary font-semibold text-3xl mb-12 mt-4">Edit Profile</h1>
    
    <div class="flex flex-wrap -mx-4">
        <!-- left column -->
        <div class="w-full md:w-1/4 px-4">
            <div class="text-center">
                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" class=" w-56 h-56 rounded-full border border-gray-300" alt="avatar">
                <h6 class="mt-2 mr-32">Upload a different photo...</h6>
                
                <input type="file" class="mt-2 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-cyan-150 file:text-black-700 ">
            </div>
        </div>
        
        <!-- edit form column -->
        <div class="w-full md:w-2/4 px-4">
            <?php if (isset($errorMessage)) : ?>
                <div class="bg-red-100 border-red-500 text-red-700 px-4 py-3 mb-4 rounded" role="alert">
                    <p class="font-bold">Error:</p>
                    <p class="text-sm"><?php echo $errorMessage; ?></p>
                </div>
            <?php endif; ?>
            <h3 class="text-xl mb-4 font-semibold">Personal info</h3>
                
            <form class="space-y-4" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="flex items-center">
                    <label class="w-1/3 md:w-1/4 text-right pr-4">Full name</label>
                    <div class="flex-1 flex space-x-4">
                        <input class="w-1/2 border border-gray-300 p-2 rounded-md" type="text" placeholder="First name" name="name" value="<?php echo $name; ?>">
                        <input class="w-1/2 border border-gray-300 p-2 rounded-md" type="text" placeholder="Last name" name="surname" value="<?php echo $surname; ?>">
                    </div>
                </div>
                <div class="flex items-center">
                    <label class="w-1/3 md:w-1/4 text-right pr-4">Email</label>
                    <div class="flex-1">
                        <input class="w-full border border-gray-300 p-2 rounded-md" type="text" name="email" value="<?php echo $email; ?>" >
                    </div>
                </div>
                <div class="flex items-center">
                    <label class="w-1/3 md:w-1/4 text-right pr-4">Company</label>
                    <div class="flex-1">
                        <input class="w-full border border-gray-300 p-2 rounded-md" type="text" value="Starlabs" disabled>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                <a href="<?php echo $href; ?>"><button class="bg-cyan-500 text-white font-bold py-2 px-4 rounded mr-2" type="button">Cancel</button></a>                    <button class="bg-blue-500 text-white font-bold py-2 px-4 rounded" type="submit">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
