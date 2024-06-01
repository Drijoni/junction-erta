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
    foreach($results as $result){
  ?>
    <!-- List Section -->
    <div class="flex flex-col w-72 bg-white rounded p-4 mr-4">
      <!-- List Name -->
      <h2 class="font-bold mb-2"><?= $result['list_name']?></h2>
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
      
            foreach($tasks as $task){
      ?>

            <!-- TASK CARD -->
            <div class="bg-blue-100 rounded p-2 mb-2 cursor-pointer relative h-26">
                <h3 class="font-bold"><?=$task['name']?></h3>
                <span class="material-symbols-outlined">subject</span>
                <span class="material-symbols-outlined">attachment</span>
                <img class="h-8 w-8 rounded-full absolute bottom-2 right-2" src="https://via.placeholder.com/150" alt="Profile Picture 1">
            </div>

        <?php }?>

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

</script>
