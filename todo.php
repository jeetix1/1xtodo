<?php
  if (isset($_POST['task'])) {
    $tasks = file_get_contents('tasks.txt');
    $tasks = explode("\n", $tasks);
    $tasks[] = $_POST['task'];
    $tasks = implode("\n", $tasks);
    file_put_contents('tasks.txt', $tasks);
  } else if (isset($_POST['completed_task'])) {
    $completed_task = $_POST['completed_task'];
    $tasks = file_get_contents('tasks.txt');
    $tasks = explode("\n", $tasks);
    $completed_tasks = file_get_contents('completed_tasks.txt');
    $completed_tasks = explode("\n", $completed_tasks);
    $timestamp = date('Y-m-d H:i:s');
    $completed_tasks[] = "$completed_task $timestamp";
    $completed_tasks = implode("\n", $completed_tasks);
    file_put_contents('completed_tasks.txt', $completed_tasks);
    $key = array_search($completed_task, $tasks);
    unset($tasks[$key]);
    $tasks = implode("\n", $tasks);
    file_put_contents('tasks.txt', $tasks);
  }
  header("Location: index.php");
  exit;
?>
