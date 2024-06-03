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

    <?php
    // Fetch notifications newer than 24 hours
    $sql1 = "SELECT * FROM notifications WHERE timestamp >= NOW() - INTERVAL 24 HOUR";
    $result1 = $conn->query($sql1);


    // Check if there are any new notifications
    if ($result1->num_rows > 0) {
      while ($row = $result1->fetch_assoc()) {
        // Determine background color based on action
        $bg_color = '';
        switch ($row["action"]) {
          case 'created':
            $bg_color = 'bg-green-200';
            break;
          case 'deleted':
            $bg_color = 'bg-red-200';
            break;
          case 'updated':
            $bg_color = 'bg-blue-200';
            break;
          default:
            $bg_color = 'bg-gray-200'; // Default color if action is not recognized
        }

        // Output the details in the specified div with the appropriate background color
        echo '<div class="' . $bg_color . ' text-gray-600 w-full py-2 px-4 center my-2 rounded-lg">';
        echo '<strong>Action:</strong> ' . $row["action"] . " | " . $row['message'] . '<br>';
        echo '<strong>Item Type:</strong> ' . $row["item_type"];
        echo '</div>';
      }
    }
    ?>
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
            <div class="mt-4"><!--<?php echo htmlspecialchars($img); ?>-->
              <img src="./assets/paysera.png" alt="Project Image" class="w-full h-48 object-cover rounded">
            </div>
            <div class="h-40 w-full flex flex-col justify-between">
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
            </div>
            <!-- Add an image here -->
            <div class="p-1">
            </div>

          </div>

        </div>
        
    <?php
    break;  
    }
    } else {
      echo "No projects found.";
    }
    $conn->close();
    ?>
  <div class="max-w-md flex flex-col justify-between bg-white rounded-md shadow-md overflow-hidden relative">
          <div class="px-6 py-6">
            <div class="text-sm text-gray-500">
              Deadline: <time datetime="<?php echo htmlspecialchars($deadline); ?>"><?php echo htmlspecialchars($deadline); ?></time>
            </div>
            <div class="mt-4"><!--<?php echo htmlspecialchars($img); ?>-->
              <img src="./assets/paypal.png" alt="Project Image" class="w-full h-48 object-cover rounded">
            </div>
            <div class="h-40 w-full flex flex-col justify-between">
              <div class="flex flex-col my-4">
                <div class="mt-2 text-2xl font-semibold text-gray-900 text-center">
                  Shkolla Digjitale Project
                </div>
                <div class="text-center text-gray-500">
                  Designing the logo
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
            </div>
            <!-- Add an image here -->
            <div class="p-1">
            </div>

          </div>

        </div>
        <div class="max-w-md flex flex-col justify-between bg-white rounded-md shadow-md overflow-hidden relative">
          <div class="px-6 py-6">
            <div class="text-sm text-gray-500">
              Deadline: <time datetime="<?php echo htmlspecialchars($deadline); ?>"><?php echo htmlspecialchars($deadline); ?></time>
            </div>
            <div class="mt-4"><!--<?php echo htmlspecialchars($img); ?>-->
              <img src="./assets/booking.png" alt="Project Image" class="w-full h-48 object-cover rounded">
            </div>
            <div class="h-40 w-full flex flex-col justify-between">
              <div class="flex flex-col my-4">
                <div class="mt-2 text-2xl font-semibold text-gray-900 text-center">
                  Erta Digital
                </div>
                <div class="text-center text-gray-500">
                 Create the best software
                </div>
              </div>
              <div class="mt-2">
                <div class="text-sm font-semibold text-gray-900">
                  Progress
                </div>
                <div class="h-2 w-full bg-gray-200 rounded-full">
                  <div class="h-full w-1/1 bg-blue-500 rounded-full"></div>
                </div>
              </div>
            </div>
            <!-- Add an image here -->
            <div class="p-1">
            </div>

          </div>

        </div>
        


        <div class="max-w-md flex flex-col justify-between bg-white rounded-md shadow-md overflow-hidden relative">
          <div class="px-6 py-6">
            <div class="text-sm text-gray-500">
              Deadline: <time datetime="<?php echo htmlspecialchars($deadline); ?>"><?php echo htmlspecialchars($deadline); ?></time>
            </div>
            <div class="mt-4"><!--<?php echo htmlspecialchars($img); ?>-->
              <img src="../../erta.png" alt="Project Image" class="w-full h-48 object-cover rounded">
            </div>
            <div class="h-40 w-full flex flex-col justify-between">
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
            </div>
            <!-- Add an image here -->
            <div class="p-1">
            </div>

          </div>

        </div>        <script src="https://cdn.botpress.cloud/webchat/v1/inject.js"></script>
          <script src="https://mediafiles.botpress.cloud/5c3f49fc-7d2d-4bd7-91be-70b02c62ef09/webchat/config.js" defer></script>

  </div>
</div>



<script>
  document.getElementById('create-project-button').addEventListener('click', function() {
    document.getElementById('create-project-modal').classList.remove('hidden');
  });

  document.getElementById('close-project-modal-button').addEventListener('click', function() {
    document.getElementById('create-project-modal').classList.add('hidden');
  });
</script>