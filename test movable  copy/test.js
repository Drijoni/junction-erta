$(document).ready(function() {
    var $taskSections = $('.task-section');
    var $tasks = $('.task');
  
    // Initialize sortable for tasks within each section
    $taskSections.each(function() {
      var $section = $(this);
      $section.sortable({
        items: ".task:not(.no-sort)",
        disabled: false,
        connectWith: ".task-section",
        start: function(event, ui) {
          ui.item.addClass("dragging");
        },
        stop: function(event, ui) {
          ui.item.removeClass("dragging");
        },
        receive: function(event, ui) {
          var $task = ui.item;
          var $targetSection = $(this);
          var $targetList = $targetSection.find('.task');
  
          // Create a clone of the task
          var $clone = $task.clone();
          $clone.attr('id', 'clone-' + $task.attr('id'));
  
          // Append the clone to the target section's list of tasks
          $targetList.last().after($clone);
  
          // Remove the original task from the source section
          $task.remove();
        }
      });
    });
  
    // Make the 3-dot control not sortable
    $(".task-actions").addClass("no-sort");
  });
  