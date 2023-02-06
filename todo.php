<?php
  session_start();
  if (isset($_POST['task'])) {
    $_SESSION['tasks'][] = $_POST['task'];
  }
  header("Location: index.php");
  exit;
?>
