<?php
  if (isset($_POST['task'])) {
    $tasks = file_get_contents('tasks.txt');
    $tasks = explode("\n", $tasks);
    $tasks[] = $_POST['task'];
    $tasks = implode("\n", $tasks);
    file_put_contents('tasks.txt', $tasks);
  }
  header("Location: index.php");
  exit;
?>
