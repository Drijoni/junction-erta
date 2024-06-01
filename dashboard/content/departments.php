<?php

$query = "SELECT name, description, priority, deadline FROM departments";
$result = $conn->query($query);

?>

<div class="w-full px-4">
  <div class="flex flex-col">
    <div class="flex flex-row w-full h-12 bg-white rounded-md items-center justify-between px-4 mt-4">
      <span class="font-bold">Departaments</span>
      <button id="create-department-button" class="px-2 py-1.5 bg-cyan-500 rounded-md text-white">Create new Departament</button>
    </div>

    <div class="flex flex-row gap-8 w-full h-36 bg-white rounded-md items-center p-4 mt-4">
      <!--Total Departaments-->
      <div class="flex flex-col border border-slate-200 justify-center items-center w-48 h-full rounded-md">
        <span class="font-bold text-3xl text-start">52</span>
        <span>total departaments</span>
      </div>

      <!--UI/UX Departaments-->
      <div class="flex flex-col border border-slate-200 justify-center items-center w-48 h-full rounded-md">
        <span class="font-bold text-3xl text-start">32</span> 
        <span>UI/UX departaments</span>
      </div>

      <!--Canva Departaments-->
      <div class="flex flex-col border border-slate-200 justify-center items-center w-48 h-full rounded-md">
        <span class="font-bold text-3xl text-start">89</span>
        <span>canva departaments</span>
      </div>
    </div>
  </div>

  <div class="departamentList mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    <?php
    // Loop to fetch and display department data
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
    ?>
    <div class="max-w-md bg-white rounded-md shadow-md overflow-hidden relative">
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

<!-- Modal (Initially Hidden) -->
<div id="create-department-modal" class="hidden fixed inset-0 flex items-center justify-center min-h-screen bg-slate-300 bg-opacity-50">
  <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
    <div class="flex flex-row items-center justify-between mb-6">
      <h2 class="text-xl font-bold text-gray-800">Create New Departament</h2>
      <button id="close-modal-button" class="material-symbols-outlined text-md">close</button>
    </div>
    <form action="./content/create_department.php" method="POST" enctype="multipart/form-data">
      <!-- Name -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2" for="name">Name</label>
        <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" type="text" id="name" name="name" required>
      </div>
      <!-- Description -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2" for="description">Description</label>
        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" id="description" name="description" rows="4" required></textarea>
      </div>
      <!-- Image Upload -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2" for="image">Upload Image</label>
        <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" type="file" id="image" name="image" accept="image/*">
      </div>
      <!-- File Upload -->
      <div class="mb-6">
        <label class="block text-gray-700 font-semibold mb-2" for="departmenType">Departament Type</label>
        <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" id="departmenType" name="departmenType" placeholder="Ex. UI/UX, VFX/GFX" rows="4" required>
      </div>
      <!-- Submit Button -->
      <div>
        <button class="w-full bg-indigo-500 text-white py-2 px-4 rounded-md hover:bg-indigo-600 focus:outline-none focus:bg-indigo-600" type="submit">Submit</button>
      </div>
    </form>
  </div>
</div>

<script>
  document.getElementById('create-department-button').addEventListener('click', function () {
    document.getElementById('create-department-modal').classList.remove('hidden');
  });

  document.getElementById('close-modal-button').addEventListener('click', function () {
    document.getElementById('create-department-modal').classList.add('hidden');
  });
</script>
