<div class="flex flex-col bg-slate-100 w-80 h-screen border-r border-slate-300 gap-6 pt-14 transition-all" id="sidebar">
    <!-- Dashboard -->
    <a href="?dashboard" class="visible flex flex-row items-center gap-2 py-2 px-4 mx-4 rounded hover:bg-blue-100">
        <span class="material-symbols-outlined">
            space_dashboard
        </span>
        <span class="menu-title-text text-lg font-semibold">
            Dashboard
        </span>
    </a>

    <?php
        if (isset($_SESSION['role']) && $_SESSION['role'] == 1) {
    ?>
    <!-- Users -->
    <a href="?user-management" class="visible flex flex-row items-center gap-2 py-2 px-4 mx-4 rounded hover:bg-blue-100">
        <span class="material-symbols-outlined">
            group
        </span>
        <span class="menu-title-text text-lg font-semibold">
            User Management
        </span>
    </a>
    <?php }?>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] !== 4) { ?>
    <!-- Clients -->
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] !== 3) {?>
    <a href="?client-management" class="visible flex flex-row items-center gap-2 py-2 px-4 mx-4 rounded hover:bg-blue-100">
        <span class="material-symbols-outlined">
            supervisor_account
        </span>
        <span class="menu-title-text text-lg font-semibold">
            Client Management
        </span>
    </a>

    
    <!-- Departments -->
    <a href="?departments" class="visible flex flex-row items-center gap-2 py-2 px-4 mx-4 rounded hover:bg-blue-100">
        <span class="material-symbols-outlined">
            linked_services
        </span>
        <span class="menu-title-text text-lg font-semibold">
            Departments
        </span>
    </a>
    <?php }?>
    
    <!-- Projects -->
    <a href="?projects" class="visible flex flex-row items-center gap-2 py-2 px-4 mx-4 rounded hover:bg-blue-100">
        <span class="material-symbols-outlined">
            tactic
        </span>
        <span class="menu-title-text text-lg font-semibold">
            Projects
        </span>
    </a>
    <?php }?>
</div>

<script>
      function toggleSidebar() {
    var sidebar = document.getElementById('sidebar');
    var menuItems = document.querySelectorAll('.menu-title-text');
    var currentWidth = sidebar.offsetWidth;
    sidebar.classList.toggle('w-24');
    sidebar.classList.toggle('w-80');
    menuItems.forEach(function(item) {
    item.classList.toggle("hidden");
  });  }
</script>