<?php

$taskboard_id = isset($_GET['taskboard']) ? intval($_GET['taskboard']) : 0;

if ($taskboard_id > 0) {
  $query = "SELECT * FROM board_list WHERE project_id = ?";

  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $taskboard_id);
  $stmt->execute();
  $results = $stmt->get_result();

  $stmt->close();
} else {
  echo "Invalid taskboard ID.";
  exit;
}

?>


<div class="w-full px-4">
  <div class="flex flex-col">
    <div class="flex flex-row w-full h-12 bg-white rounded-md items-center justify-between px-4 mt-4">
      <span class="font-bold">Board</span>
      <button id="create-department-button" class="px-2 py-1.5 bg-cyan-500 rounded-md text-white">Add another List</button>
    </div>
  </div>

  <div class="flex h-full bg-gray-200 py-5">

    <?php
    foreach ($results as $result) {
    ?>
      <!-- List Section -->
      <div class="flex flex-col w-72 bg-white rounded p-4 mr-4">
        <!-- List Name -->
        <h2 class="font-bold mb-2"><?= $result['list_name'] ?></h2>
        <div>
          <!--Tasks Displayed Here-->
          <?php
          $boardlist_id = $result['id'];

          $sql = "SELECT t.* FROM tasks t
        INNER JOIN board_task_relation btr ON t.id = btr.task_id
        WHERE btr.project_id = ? AND btr.boardlist_id = ?";


          $stmt = $conn->prepare($sql);
          $stmt->bind_param("ii", $taskboard_id, $boardlist_id);
          $stmt->execute();
          $tasks = $stmt->get_result();

          foreach ($tasks as $task) {
          ?>

            <!-- TASK CARD -->
            <div class="bg-blue-100 rounded p-2 mb-2 cursor-pointer relative h-26 open-task" data-task-id="<?= $task['id'] ?>" data-task-name="<?= $task['name'] ?>" data-task-description="<?= $task['description'] ?>" data-task-priority="<?= $task['priority'] ?>" data-task-img="<?= $task['img'] ?>" onclick="openTask(this)">
              <h3 class="font-bold"><?= $task['name'] ?></h3>
              <span class="material-symbols-outlined">subject</span>
              <span class="material-symbols-outlined">attachment</span>
              <img class="h-8 w-8 rounded-full absolute bottom-2 right-2" src="https://via.placeholder.com/150" alt="Profile Picture 1">
            </div>


          <?php } ?>

          <form class="flex flex-col" action="./content/create_task.php" method="POST">
            <input type="hidden" name="project_id" value="<?= $taskboard_id ?>">
            <input type="hidden" name="boardlist_id" value="<?= $result['id'] ?>">
            <input type="hidden" name="list_position" class="list-position">
            <input type="text" name="task_name" class="mb-4 p-2 rounded border" placeholder="Write task...">
            <button type="submit" class="text-blue-500 hover:underline">+ Add another card</button>
          </form>

        </div>

      </div>
    <?php } ?>




    <!-- Task modal -->
    <div id="taskModal" class="hidden fixed inset-0 flex items-center justify-center min-h-screen bg-slate-300 bg-opacity-50">
      <div class="bg-white p-8 rounded shadow-md w-128 relative">
        <span id="closeBtn" class="absolute top-0 text-5xl right-2 text-gray-500 font-5xl hover:text-gray-700 cursor-pointer">&times;</span> <!-- Close button -->
        <div class="p-5 border rounded max-w-xxl mx-auto"> <!-- Increased width -->
          <h1 class="text-3xl mb-4 text-gray-600 font-high"><span class="material-symbols-outlined align-middle mt-0">web_asset</span> Edit task</h1> <!-- Added symbol and adjusted vertical alignment -->
          <p class="text-gray-500 text-sm mb-4">This is some small grey text.</p> <!-- Added small grey text -->
          <div class="flex justify-between">
            <div class="mr-4">
              <div class="flex items-center mb-2"> <!-- Added div with flex class -->
                <span class="material-symbols-outlined">subject</span>
                <label for="name" class="block text-xl font-medium text-gray-600 ml-2">Name</label> <!-- Added label -->
              </div>
              <input id="name" type="text" class="w-64 p-2 mb-4 border rounded" placeholder="name"></input> <!-- Adjusted width -->
              <div class="flex items-center mb-2"> <!-- Added div with flex class -->
                <span class="material-symbols-outlined">checklist_rtl</span>
                <h2 class="text-xl font-medium text-gray-600 ml-2">Description</h2>
              </div>
              <textarea class="w-64 h-32 p-2 border rounded" id="description" placeholder="Describe your issue"></textarea> <!-- Adjusted width -->
            </div>
            <div class="mr-6"> <!-- Added right margin -->
              <h2 class="text-xl mb-4 text-gray-600 font-medzium">
                <div class="flex items-center justify-between mb-2"> <!-- Added justify-between class -->
                  <div class="flex items-center"> <!-- Added div with flex class -->
                    <span class="material-symbols-outlined">person</span>
                    <h2 class="text-xl font-medium text-gray-600 ml-2 mx-6">Members</h2>
                  </div>
                  <button class="bg-blue-300 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-sm">
                    Assign Members
                  </button>
                </div>
                <!-- Member cards go here -->
                <div class="p-2 border rounded mb-2">Member 1</div>
                <div class="p-2 border rounded mb-2">Member 2</div>
                <div class="p-2 border rounded mb-2">Member 3</div>
              </h2>
            </div>
          </div>
        </div>
      </div>
    </div>




    <!-- Add list card -->
    <div id="add-list-card" class="flex flex-col w-72 h-40 bg-white rounded p-4 mr-4 justify-between hidden">
      <form class="flex flex-col w-full h-full justify-between" action="./content/create_list.php" method="Post">
        <input type="hidden" name="project_id" id="project_id">
        <input type="hidden" name="list_position" id="list_position">
        <input type="text" name="list_name" placeholder="Enter list title..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500">
        <button class="w-full px-2 py-1.5 bg-cyan-500 rounded-md text-white">Create List</button>
      </form>
    </div>
  </div>
</div>

<script>
  document.getElementById("create-department-button").addEventListener("click", function() {
    var addListCard = document.getElementById("add-list-card");
    if (addListCard.classList.contains("hidden")) {
      addListCard.classList.remove("hidden");
    } else {
      addListCard.classList.add("hidden");
    }
  });

  window.onload = function() {
    var urlParams = new URLSearchParams(window.location.search);
    var projectId = urlParams.get('taskboard');
    document.getElementById('project_id').value = projectId;

    var lists = document.getElementsByClassName("w-72");
    for (var i = 0; i < lists.length; i++) {
      var tasks = lists[i].getElementsByTagName("form").length;
      lists[i].getElementsByClassName("list-position")[0].value = tasks + 1;
    }
  };

  //modal
  function openTask(taskElement) {
    var taskName = $(taskElement).data('task-name');
    var taskDescription = $(taskElement).data('task-description');

    // Set input values with task data
    $('#name').val(taskName);
    $('#description').text(taskDescription);

    $('#taskModal').removeClass('hidden');
  }

  var modal = document.getElementById("taskModal");

  // Add event listener to close button
  closeBtn.addEventListener("click", function() {
    // Hide the modal
    modal.classList.add("hidden");
  });
</script>