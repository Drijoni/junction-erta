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



$query = "SELECT id FROM project_department_relations WHERE project_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $taskboard_id);
$stmt->execute();
$departments = $stmt->get_result();

// Check if there are any rows returned
if ($departments->num_rows > 0) {
    while ($row = $departments->fetch_assoc()) {
        $departmentId = $row['id'];
    }
} else {
    echo "No departments found for the given project ID.";
}


function getRandomImageSrc() {
  $randomNumber = rand(1, 6); // Generate a random number between 1 and 6
  return "./assets/$randomNumber.png"; // Construct the source path
}

// Close the statement
$stmt->close();



?>
<head>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</head>

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
        <h2 class="font-bold mb-2"><?= $result['list_name'] ?></h2>
        <div data-tasklist-id="<?= $result['id'] ?>" class="taskList">
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
            <div class="bg-blue-100 rounded p-2 mb-2 cursor-pointer relative h-26 open-task" data-department-id="<?=$departmentId?>" data-task-id="<?= $task['id'] ?>" data-task-name="<?= $task['name'] ?>" data-task-description="<?= $task['description'] ?>" data-task-priority="<?= $task['priority'] ?>" data-task-img="<?= $task['img'] ?>" onclick="openTask(this)">
              <h3 class="font-bold"><?= $task['name'] ?></h3>
              <span class="material-symbols-outlined">subject</span>
              <span class="material-symbols-outlined">attachment</span>
              <?php
              // Usage example in an HTML context
              $imageSrc = getRandomImageSrc(); // Call the function to get a random image source
              echo "<img class='h-8 w-8 rounded-full absolute bottom-2 right-2' src='$imageSrc' alt='Profile Picture'>";
              ?>
            </div>

          <?php } ?>
          </div>

          <form class="flex flex-col" action="./content/create_task.php" method="POST">
            <input type="hidden" name="project_id" value="<?= $taskboard_id ?>">
            <input type="hidden" name="boardlist_id" value="<?= $result['id'] ?>">
            <input type="hidden" name="list_position" class="list-position">
            <input type="text" name="task_name" class="mb-4 p-2 rounded border" placeholder="Write task...">
            <button type="submit" class="text-blue-500 hover:underline">+ Add another card</button>
          </form>


      </div>
    <?php } ?>




    <!-- Task modal -->
    <div id="taskModal" class="hidden fixed inset-0 flex items-center justify-center min-h-screen bg-slate-300 bg-opacity-50">
      <div class="bg-white p-8 rounded shadow-md w-128 relative">
        <span id="closeBtn" class="absolute top-0 text-4xl right-2 text-gray-500 font-5xl hover:text-gray-700 cursor-pointer">&times;</span> <!-- Close button -->
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
              <div class="flex flex-col">
                <textarea class="w-64 h-32 p-2 border rounded" id="description" placeholder="Describe your issue"></textarea> <!-- Adjusted width -->
                <div class="logo-erta visible flex flex-row items-center">
                  <img src="../../erta.png" width="20" alt="">
                  <span class="text-blue-900 font-bold">Erta - AI</span>
                </div>
                <button id="ai_btn" class="transition-all opacity-0 text-xs bg-purple-950 text-white p-2 my-2 w-32">Enhance with AI</button>
              </div>
            </div>
            <form action="./content/user_task_relations.php" method="post">
              <input type="hidden" id="hiddenTaskID" name="taskID">
              <input type="hidden" name="hiddenProjectID" id="projectID">
              <div class="mr-6"> 
                <h2 class="text-xl mb-4 text-gray-600 font-medzium">
                  <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center">
                      <span class="material-symbols-outlined">person</span>
                      <h2 class="text-xl font-medium text-gray-600 ml-2 mx-6">Members</h2>
                    </div>
                    <button class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-1 px-2 rounded text-sm">
                      Assign Members
                    </button>
                  </div>
                  <select name="userID" class="p-2 border rounded mb-2 w-full">
                    <option value="0">Select User</option>
                  </select>
                  <!-- Member cards go here -->
                  <div id="taskMembersContainer"></div>
                </h2>
              </div>
            </form>
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

$(document).ready(function() {
    $('.open-task').draggable({
        revert: "invalid",
        containment: "document",
        helper: "clone",
        cursor: "move"
    });

    $('.taskList').droppable({
        accept: ".open-task",
        hoverClass: "drop-hover",
        drop: function(event, ui) {
            var droppedOn = $(this);
            var dragged = ui.draggable;
            // Using data-tasklist-id attribute to get the list ID correctly
            var oldListId = dragged.closest('.taskList').data('tasklist-id');
            var newListId = droppedOn.data('tasklist-id');

            // Console log the IDs to check if they are correct
            console.log("Dragging Task ID: " + dragged.data('task-id')); // Log the task ID
            console.log("Old List ID: " + oldListId); // Log the original list ID
            console.log("New List ID: " + newListId); // Log the new list ID

            // Move the task card to the new list
            dragged.detach().css({top: 0, left: 0}).appendTo(droppedOn);

            // Perform the AJAX call to update the backend
            $.ajax({
                url: 'content/update_taskList_position.php',
                method: 'POST',
                data: {
                    task_id: dragged.data('task-id'),
                    new_list_id: newListId
                },
                success: function(response) {
                    console.log('Update successful', response);
                },
                error: function(xhr, status, error) {
                    console.error('Error updating task position:', error);
                }
            });
        }
    });
});




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
//modal
function openTask(taskElement) {
  
    var id = $(taskElement).data('task-id');
    var taskName = $(taskElement).data('task-name');
    var taskDescription = $(taskElement).data('task-description');
    var departmentId = $(taskElement).data('department-id'); // Retrieve department_id

    document.getElementById('hiddenTaskID').value = id;

    var urlParams = new URLSearchParams(window.location.search);
    document.getElementById('projectID').value = urlParams.get('taskboard');

    // Set input values with task data
    $('#name').val(taskName);
    $('#description').text(taskDescription);

    $('#taskModal').removeClass('hidden');

    $.ajax({
        url: 'content/fetch_task_members.php',
        type: 'POST',
        data: { department_id: departmentId }, // Use departmentId
        success: function(response) {
            var departmentMembers = JSON.parse(response);
            var membersHtml = '<option value="0">Select User</option>';

            $.each(departmentMembers, function(index, member) {
                membersHtml += '<option value="' + member.id + '">' + member.name + ' ' + member.surname + '</option>';
            });

            $('select[name="userID"]').html(membersHtml);
        },
        error: function(xhr, status, error) {
            console.error('Error fetching department members:', error);
        }
    });

    $.ajax({
    url: 'content/fetch_user_task_relations.php',
    type: 'POST',
    data: { task_id: id },
    dataType: 'json', // Automatically parse the JSON response
    success: function(taskMembers) {
        var membersHtml = '';
        if (taskMembers.error) {
            console.error('Server error:', taskMembers.error);
            $('#taskMembersContainer').html('<div>Error loading task members.</div>');
        } else {
            $.each(taskMembers, function(index, member) {
                membersHtml += '<div class="p-2 border rounded mb-2">' + member.name + ' ' + member.surname + '</div>';
            });
            $('#taskMembersContainer').html(membersHtml);
        }
    },
    error: function(xhr, status, error) {
        console.error('AJAX error:', error, 'Status:', status);
        console.log('Server response:', xhr.responseText);
    }
});

  }



  var modal = document.getElementById("taskModal");

  // Add event listener to close button
  closeBtn.addEventListener("click", function() {
    // Hide the modal
    modal.classList.add("hidden");
  });

  const textarea = document.getElementById('description');
  const ai_btn = document.getElementById('ai_btn');

textarea.addEventListener('input', function() {
  ai_btn.classList.remove('opacity-0');
  ai_btn.classList.add('opacity-100');
  $('.logo-erta').hide();
});

//ajax call
$(document).ready(function() {
  $('#ai_btn').click(function() {
    var prompt = $('#description').val();
    $.ajax({
      type: 'POST',
      url: './ai/help-ai.php',
      data: {
        prompt: prompt
      },
      success: function(response) {
        // Parse the JSON response
        var data = JSON.parse(response);

        // Extract the tasks from the response
        var tasks = data.text.split(', ');

        // Display the tasks as needed
        console.log(tasks); // Log the tasks to the console

        // Example: Create an unordered list and append the tasks
        var taskList = '';
        $.each(tasks, function(index, task) {
          taskList += '- ' + task + '\n';
        });

        // Set the tasks to the input field
        $('#description').val(taskList);
      },
      error: function(xhr, status, error) {
        console.error(error);
      }
    });
  });
});



</script>