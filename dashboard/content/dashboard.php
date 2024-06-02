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
   
    <div class="flex flex-row gap-8 w-full h-36 bg-white rounded-md items-center p-4 mt-4">
      <!--Total Projects-->
      <div class="flex flex-col border border-slate-200 justify-center items-center w-48 h-full rounded-md">
        <span class="font-bold text-3xl text-start">52</span>
        <span>total projects</span>
      </div>

      <!--UI/UX Departments-->
      
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
    <div class="mt-4">
      <img src="<?php echo htmlspecialchars($img); ?>" alt="Project Image" class="w-full h-48 object-cover">
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
    <!-- Add an image here -->
    <div class="p-4">
  <p><strong>Description:</strong> <?php echo htmlspecialchars($description); ?></p>
</div>
  
</div>
<script src="https://cdn.botpress.cloud/webchat/v1/inject.js"></script>
<script src="https://mediafiles.botpress.cloud/5c3f49fc-7d2d-4bd7-91be-70b02c62ef09/webchat/config.js" defer></script>

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



<script>
  document.getElementById('create-project-button').addEventListener('click', function () {
    document.getElementById('create-project-modal').classList.remove('hidden');
  });

  document.getElementById('close-project-modal-button').addEventListener('click', function () {
    document.getElementById('create-project-modal').classList.add('hidden');
  });
</script>
