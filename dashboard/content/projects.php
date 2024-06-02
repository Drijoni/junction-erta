<?php

$query = "SELECT name, description, priority, deadline, img FROM projects";
$result = $conn->query($query);

$query_departments = "SELECT id, name FROM departments";
$result_departments = $conn->query($query_departments);

// Updated query to fetch projects with their associated department names
$query = "
    SELECT p.id as project_id, p.name, p.description, p.priority, p.deadline, p.img, d.name AS department_name
    FROM projects p
    JOIN project_department_relations pd ON p.id = pd.project_id
    JOIN departments d ON pd.department_id = d.id
";
$result = $conn->query($query);

?>

<div class="w-full px-4">
  <div class="flex flex-col">
    <div class="flex flex-row w-full h-12 bg-white rounded-md items-center justify-between px-4 mt-4">
      <span class="font-bold">All Projects</span>
      <button id="create-project-button" class="px-2 py-1.5 bg-cyan-500 rounded-md text-white">Create new Project</button>
    </div>

    <div class="flex flex-row gap-8 w-full h-36 bg-white rounded-md items-center p-4 mt-4">
      <!--Total Projects-->
      <div class="flex flex-col border border-slate-200 justify-center items-center w-48 h-full rounded-md">
        <span class="font-bold text-3xl text-start">52</span>
        <span>total projects</span>
      </div>

      <!--UI/UX Departments-->
      <div class="flex flex-col border border-slate-200 justify-center items-center w-48 h-full rounded-md">
        <span class="font-bold text-3xl text-start">32</span>
        <span>UI/UX departments</span>
      </div>

      <!--Canva Departments-->
      <div class="flex flex-col border border-slate-200 justify-center items-center w-48 h-full rounded-md">
        <span class="font-bold text-3xl text-start">89</span>
        <span>canva departments</span>
      </div>
    </div>
  </div>

  <div class="departamentList mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
  <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $deadline = $row['deadline'] ?? '';
            $name = $row['name'] ?? '';
            $description = $row['description'] ?? '';
            $department_name = $row['department_name'] ?? '';
            $img = $row['img'] ?? '';
            $priority = $row['priority'] ?? '';
            $project_id = $row['project_id'] ?? '';
    ?>
    <div class="max-w-md flex flex-col justify-between bg-white rounded-md shadow-md overflow-hidden relative">
      <div class="px-6 py-6">
        <div class="text-sm text-gray-500">
          Deadline: <time datetime="<?php echo htmlspecialchars($deadline); ?>"><?php echo htmlspecialchars($deadline); ?></time>
        </div>
        <div class="flex flex-col my-4">
          <div class="mt-2 text-2xl font-semibold text-gray-900 text-center">
            <?php echo htmlspecialchars($name); ?> Project
          </div>
          <div class="text-center text-gray-500">
            <?php echo htmlspecialchars($description); ?>
          </div>
        </div>
        <div class="mt-2">
          <div class="text-sm font-semibold text-gray-900">
            Progress
          </div>
          <div class="h-2 w-full bg-gray-200 rounded-full">
            <div class="h-full w-1/2 bg-blue-500 rounded-full"></div>
          </div>
        </div>
        <div class="mt-6">
          <div class="text-sm font-semibold text-gray-900">
            Department: <?php echo htmlspecialchars($department_name); ?>
          </div>
        </div>
        <hr class="mt-3 mb-6 max-w-md">
        <div class="flex flex-row justify-between items-center">
          <div class="mt-4 flex space-x-2">
              <img class="h-8 w-8 rounded-full" src="<?php echo htmlspecialchars($img); ?>" alt="Project Image">
            </div>
            <div class="text-black px-2 py-1 rounded">
              Priority: 
              <span class="<?php echo htmlspecialchars(
                  $priority == 'Low' ? 'text-green-500' : 
                  ($priority == 'Medium' ? 'text-yellow-500' : 'text-red-500')
              ); ?>">
                  <?php echo htmlspecialchars($priority); ?>
              </span>
          </div>
        </div>
      </div>
      <!--Buttons-->
      <div class="flex flex-col w-full gap-4 p-8">
          <a href="#" class="w-full flex justify-center items-center py-2 bg-blue-500 text-white rounded-md ">Edit Project</a>
          <a href="?taskboard=<?=$project_id?>" class="w-full flex justify-center items-center py-2 bg-blue-500 text-white rounded-md">Task Board</a> 
      </div>
    </div>
<?php
    }
} else {
    echo "No projects found.";
}
$conn->close();
?>


  </div>
</div>

<div id="create-project-modal" class="hidden fixed inset-0 flex items-center justify-center min-h-screen bg-slate-300 bg-opacity-50">
  <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
    <div class="flex flex-row items-center justify-between mb-6">
      <h2 class="text-xl font-bold text-gray-800">Create New Project</h2>
      <button id="close-project-modal-button" class="material-symbols-outlined text-md">close</button>
    </div>
    <form action="./content/save_projects.php" method="POST" enctype="multipart/form-data">
      <!-- Name -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2" for="name">Name</label>
        <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" type="text" id="create-name" name="name" required>
      </div>
      <!-- Description -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2" for="description">Description</label>
        <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" id="description" name="description" rows="4" required></textarea>
      </div>
      <!-- Department -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2" for="department">Department</label>
        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" id="department" name="department" required>
          <?php
          if ($result_departments->num_rows > 0) {
              while ($row = $result_departments->fetch_assoc()) {
                  echo "<option value=\"" . $row['id'] . "\">" . htmlspecialchars($row['name']) . "</option>";
              }
          } else {
              echo "<option value=\"\">No departments available</option>";
          }
          ?>
        </select>
      </div>
      <!-- Deadline -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2" for="deadline">Deadline</label>
        <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" type="date" id="deadline" name="deadline" required>
      </div>
      <!-- Priority -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2" for="priority">Priority</label>
        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" id="priority" name="priority" required>
          <option>Low</option>
          <option>Medium</option>
          <option>High</option>
        </select>
      </div>
      <!-- Image Upload -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2" for="img">Insert Image</label>
        <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500" type="file" id="img" name="img" accept="image/*" >
      </div>
      <!-- Submit Button -->
      <div>
        <button class="w-full bg-indigo-500 text-white py-2 px-4 rounded-md hover:bg-indigo-600 focus:outline-none focus:bg-indigo-600" type="submit">Submit</button>
      </div>
    </form>
  </div>
</div>

<script>
  document.getElementById('create-project-button').addEventListener('click', function () {
    document.getElementById('create-project-modal').classList.remove('hidden');
  });

  document.getElementById('close-project-modal-button').addEventListener('click', function () {
    document.getElementById('create-project-modal').classList.add('hidden');
  });
</script>
