<?php

$query = "SELECT * FROM departments";
$result = $conn->query($query);

$query = "SELECT * FROM users";
$users = $conn->query($query);

?>

<div class="w-full px-4">
  <div class="flex flex-col">
    <div class="flex flex-row w-full h-12 bg-white rounded-md items-center justify-between px-4 mt-4">
      <span class="font-bold">Departments</span>
      <button id="create-department-button" class="px-2 py-1.5 bg-cyan-500 rounded-md text-white">Create new Department</button>
    </div>

    <div class="flex flex-row gap-8 w-full h-36 bg-white rounded-md items-center p-4 mt-4">
      <!-- Total Departments -->
      <div class="flex flex-col border border-slate-200 justify-center items-center w-48 h-full rounded-md">
        <span class="font-bold text-3xl text-start">52</span>
        <span>total departments</span>
      </div>

      <!-- UI/UX Departments -->
      <div class="flex flex-col border border-slate-200 justify-center items-center w-48 h-full rounded-md">
        <span class="font-bold text-3xl text-start">32</span> 
        <span>UI/UX departments</span>
      </div>

      <!-- Canva Departments -->
      <div class="flex flex-col border border-slate-200 justify-center items-center w-48 h-full rounded-md">
        <span class="font-bold text-3xl text-start">89</span>
        <span>canva departments</span>
      </div>
    </div>
  </div>

  <div class="departmentList mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    <?php
    // Loop to fetch and display department data
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
    ?>
    <!-- Department Card -->
    <div 
      class="max-w-md bg-white rounded-md shadow-md overflow-hidden relative cursor-pointer" 
      data-id="<?php echo htmlspecialchars($row['id']); ?>"
      data-name="<?php echo htmlspecialchars($row['name']); ?>"
      data-description="<?php echo htmlspecialchars($row['description']); ?>"
      data-priority="<?php echo htmlspecialchars($row['priority']); ?>"
      data-deadline="<?php echo htmlspecialchars($row['deadline']); ?>"
      data-img="<?php echo htmlspecialchars($row['img']); ?>"
      onclick="openDepartment(this)"
    >
      <div class="px-6 py-6">
        <div class="flex flex-col my-4">
          <div class="mt-2 text-2xl font-semibold text-gray-900 text-center">
            <?php echo htmlspecialchars($row['name']); ?> Department
          </div>
          <div class="text-center text-gray-500">
            <?php echo htmlspecialchars($row['description']); ?>
          </div>
        </div>
        <div class="mt-2">
          <div class="text-sm font-semibold text-gray-900">
            Tasks
          </div>
          <div class="h-2 w-full bg-gray-200 rounded-full">
            <div class="h-full w-1/2 bg-blue-500 rounded-full"></div>
          </div>
        </div>
        <hr class="my-6 max-w-md">
        <div class="w-full flex items-center justify-center mt-4 flex space-x-2">
          <img class="h-8 w-8 rounded-full" src="https://via.placeholder.com/150" alt="Profile Picture 1">
          <img class="h-8 w-8 rounded-full" src="https://via.placeholder.com/150" alt="Profile Picture 2">
          <img class="h-8 w-8 rounded-full" src="https://via.placeholder.com/150" alt="Profile Picture 3">
        </div>
      </div>
    </div>
    <?php
        } 
    } else {
        echo "No departments found.";
    }
    $conn->close();
    ?>
  </div>
</div>

<!-- Create Department Modal (Initially Hidden) -->
<div id="create-department-modal" class="hidden fixed inset-0 flex items-center justify-center min-h-screen bg-slate-300 bg-opacity-50">
  <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
    <div class="flex flex-row items-center justify-between mb-3">
      <h2 class="text-xl font-bold text-gray-800">Create New Department</h2>
      <button id="close-create-modal-button" class="material-symbols-outlined text-md">close</button>
    </div>
    <hr class="w-full mb-6">
    <form action="./content/create_department.php" method="POST" enctype="multipart/form-data">
      <!-- Name -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2" for="name">Name</label>
        <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" type="text" id="create-name" name="name" required>
      </div>
      <!-- Description -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2" for="description">Description</label>
        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" id="create-description" name="description" rows="4" required></textarea>
      </div>
      <!-- Image Upload -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2" for="image">Upload Image</label>
        <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" type="file" id="create-image" name="image" accept="image/*">
      </div>
      <!-- Department Type -->
      <div class="mb-6">
        <label class="block text-gray-700 font-semibold mb-2" for="departmenType">Department Type</label>
        <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" id="create-departmenType" name="departmenType" placeholder="Ex. UI/UX, VFX/GFX" required>
      </div>
      <!-- Submit Button -->
      <div>
        <button class="w-full bg-indigo-500 text-white py-2 px-4 rounded-md hover:bg-indigo-600 focus:outline-none focus:bg-indigo-600" type="submit">Submit</button>
      </div>
    </form>
  </div>
</div>

<!-- Modify Department Modal (Initially Hidden) -->
<div id="modify-department-modal" class="hidden fixed inset-0 flex items-center justify-center min-h-screen bg-slate-300 bg-opacity-50">
  <div class="bg-white p-8 rounded shadow-md w-128 relative">
    <span id="close-modify-modal-button" class="absolute top-0 text-5xl right-2 text-gray-500 font-5xl hover:text-gray-700 cursor-pointer">&times;</span>
    <div class="p-5 border rounded max-w-xxl mx-auto">
      <h1 class="text-3xl mb-4 text-gray-600 font-high">
        <span class="material-symbols-outlined align-middle mt-0">business</span>
        Modify Department
      </h1>
      <p class="text-gray-500 text-sm mb-4">Update department details below.</p>
      <div class="flex justify-between">
        <div class="mr-4">
          <div class="flex items-center mb-2">
            <span class="material-symbols-outlined">subject</span>
            <label for="modify-name" class="block text-xl font-medium text-gray-600 ml-2">Name</label>
          </div>
          <input id="modify-name" name="name" type="text" class="w-64 p-2 mb-4 border rounded focus:outline-none focus:border-indigo-500" placeholder="Name" required>
          <div class="flex items-center mb-2">
            <span class="material-symbols-outlined">description</span>
            <h2 class="text-xl font-medium text-gray-600 ml-2">Description</h2>
          </div>
          <textarea id="modify-description" name="description" class="w-64 h-32 p-2 border rounded focus:outline-none focus:border-indigo-500" placeholder="Describe the department" rows="4" required></textarea>
          <div class="flex items-center mb-2">
            <span class="material-symbols-outlined">image</span>
            <label for="modify-image" class="block text-xl font-medium text-gray-600 ml-2">Upload Image</label>
          </div>
          <input id="modify-image" name="image" type="file" class="w-64 p-2 mb-4 border rounded focus:outline-none focus:border-indigo-500" accept="image/*">
          <div class="flex items-center mb-2">
            <span class="material-symbols-outlined">category</span>
            <label for="modify-departmenType" class="block text-xl font-medium text-gray-600 ml-2">Department Type</label>
          </div>
          <input id="modify-departmenType" name="departmenType" type="text" class="w-64 p-2 mb-6 border rounded focus:outline-none focus:border-indigo-500" placeholder="Ex. UI/UX, VFX/GFX" required>
        </div>
        <form action="./content/user_department_relation.php" method="post">
          <input type="hidden" id="modify-id" name="departmentID"> <!-- Hidden input field for ID -->
          <div class="mr-6">
            <div class="flex items-center justify-between mb-2">
              <div class="flex items-center">
                <span class="material-symbols-outlined">person</span>
                <h2 class="text-xl font-medium text-gray-600 ml-2 mx-6">Members</h2>
              </div>
              <button class="bg-blue-300 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-sm">
                Assign Member
              </button>
            </div>
            <select name="userID" class="p-2 border rounded mb-2 w-full">
              <option value="0">Select User</option>
              <?php foreach($users as $user): ?>
                <option value="<?= $user['id'] ?>"><?= $user['name'] . ' ' . $user['surname'] ?></option>
              <?php endforeach; ?>
            </select>

            <!-- Member cards -->
            <div class="p-2 border rounded mb-2">Member 1</div>
            <div class="p-2 border rounded mb-2">Member 2</div>
            <div class="p-2 border rounded mb-2">Member 3</div>
          </div>
        </form>
      </div>
      <div>
        <button class="w-full bg-indigo-500 text-white py-2 px-4 rounded-md hover:bg-indigo-600 focus:outline-none focus:bg-indigo-600" type="submit">Submit</button>
      </div>
    </div>
  </div>
</div>


<script>
  document.getElementById('create-department-button').addEventListener('click', function () {
    document.getElementById('create-department-modal').classList.remove('hidden');
  });

  document.getElementById('close-create-modal-button').addEventListener('click', function () {
    document.getElementById('create-department-modal').classList.add('hidden');
  });

  document.getElementById('close-modify-modal-button').addEventListener('click', function () {
    document.getElementById('modify-department-modal').classList.add('hidden');
  });

  function openDepartment(element) {
    const id = element.getAttribute('data-id');
    const name = element.getAttribute('data-name');
    const description = element.getAttribute('data-description');
    const priority = element.getAttribute('data-priority');
    const deadline = element.getAttribute('data-deadline');
    const img = element.getAttribute('data-img');

    document.getElementById('modify-id').value = id; // Set the hidden input value
    document.getElementById('modify-name').value = name;
    document.getElementById('modify-description').value = description;
    document.getElementById('modify-departmenType').value = priority; // Assuming departmentType represents priority

    // Set image upload input value (this might not be directly possible due to security reasons, but if you are displaying the image somewhere, you can set the src attribute of an img element instead)

    // Open the modify modal
    document.getElementById('modify-department-modal').classList.remove('hidden');
  }
</script>
