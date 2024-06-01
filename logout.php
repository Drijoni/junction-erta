<?php
 session_start();
 include_once('config.php');
 session_destroy();
 header("Location:index.html");
?>