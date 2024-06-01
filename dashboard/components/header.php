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

// Assuming the user's ID is stored in the session under the key 'user_id'
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Fetch the current user's data from the database
    $userQuery = "SELECT * FROM users WHERE id = $userId";
    $userResult = $conn->query($userQuery);

    // Check if the query execution was successful
    if (!$userResult) {
        die("Error fetching user data: " . $conn->error);
    }

    // Fetch the current user's name and surname
    $userData = $userResult->fetch_assoc();
    $name = $userData['name'];
    $surname = $userData['surname'];
} else {
    // Handle the case where the user is not logged in or the session variable is not set
    $name = ''; // Set default values
    $surname = ''; // Set default values
}

// Close the database connection
$conn->close();
?>

<div class="bg-slate-100 flex flex-row justify-between items-center w-full h-16 border border-b-2 border-slate-300 px-8">
    <span class="material-symbols-outlined cursor-pointer">
        menu
    </span>

    <p class="text-xl font-bold"><span class="text-cyan-500">Erta</span>Flow</p>

    <div class="relative flex items-center gap-2">
        <!-- User Name Dropdown -->
        <div class="relative flex items-center">
            <div class="relative cursor-pointer flex items-center" onclick="toggleMenu('profileMenu')">
                <span><?php echo $name . ' ' . $surname; ?></span>
                <span class="material-symbols-outlined ml-2">person</span>
            </div>
            <div id="profileMenu" class="absolute hidden bg-white border border-gray-300 mt-32 py-2 w-40 rounded-md shadow-lg" style="left: -20px;">
                <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-200 hover:text-gray-900">Edit Profile</a>
                <a href="../logout.php" class="block px-4 py-2 text-gray-800 hover:bg-gray-200 hover:text-gray-900">Log Out</a>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleMenu(menuId) {
        var menu = document.getElementById(menuId);
        menu.classList.toggle('hidden');
    }
</script>
