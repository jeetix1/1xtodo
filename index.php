<?php
// Connect to database
require_once '../../1xtodo-dbcon.php';

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Reopen task
if (isset($_GET['reopen_task'])) {
    $id = $_GET['reopen_task'];
    $query = "UPDATE tasks SET status = 'incomplete' WHERE id = $id";
    mysqli_query($conn, $query);
    $log_event = "Task reopened: " . $id;
    $query = "INSERT INTO log (task_id, event) VALUES ($id, '$log_event')";
    mysqli_query($conn, $query);
}

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    $task = mysqli_real_escape_string($conn, $_POST['task']);
    $query = "INSERT INTO tasks (task) VALUES ('$task')";
    mysqli_query($conn, $query);
    $log_event = "Task added: " . $task;
    $query = "INSERT INTO log (task_id, event) VALUES (LAST_INSERT_ID(), '$log_event')";
    mysqli_query($conn, $query);
}

// Check if the finish task button has been clicked
if (isset($_GET['fin_task'])) {
    $id = $_GET['fin_task'];
    $query = "UPDATE tasks SET status = 'complete' WHERE id = $id";
    mysqli_query($conn, $query);
    $log_event = "Task finished: " . $id;
    $query = "INSERT INTO log (task_id, event) VALUES ($id, '$log_event')";
    mysqli_query($conn, $query);
}

// Change task status to "completed"
if (isset($_GET['fin_task'])) {
    $id = $_GET['fin_task'];
    $sql = "UPDATE tasks SET status='completed' WHERE id=$id";
    if (mysqli_query($conn, $sql)) {
        header('Location: index.php');
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Get all the incomplete tasks from the database
$query = "SELECT * FROM tasks WHERE status = 'incomplete'";
$tasks = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Task List</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Task List</h1>
    <button class="btn default" onclick="window.location.href='browse.php'">Browse all</button>
    <form action="index.php" method="post">
        <input type="text" name="task" placeholder="Add a task...">
        <input type="submit" name="submit" value="Add Task">
    </form>
    <table>
        <tr>
            <th>Task</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($task = mysqli_fetch_assoc($tasks)) { ?>
            <tr>
                <td><?php echo $task['task']; ?></td>
                <td><?php echo $task['status']; ?></td>
                <td>
                    <?php if ($task['status'] == 'incomplete') { ?>
                        <a href="index.php?fin_task=<?php echo $task['id']; ?>">Finish</a> | <a href="edit.php?edit_task=<?php echo $task['id']; ?>">Edit</a>
                    <?php } ?>
                    <?php if ($task['status'] == 'completed') { ?>
                        <a href="index.php?reopen_task=<?php echo $task['id']; ?>">Reopen</a>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
