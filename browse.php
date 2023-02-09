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
    $id = $_POST['id'];
    $query = "UPDATE tasks SET task = '$task' WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: browse.php");
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
        header('Location: browse.php');
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Get all the tasks from the database
$query = "SELECT tasks.id as task_id, tasks.task, tasks.status, MIN(log.timestamp) as created_at, MAX(CASE WHEN log.event = 'completed' THEN log.timestamp ELSE NULL END) as completed_at 
FROM tasks 
LEFT JOIN log ON tasks.id = log.task_id 

GROUP BY tasks.id";

$tasks = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Task List</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <div class="grid ">
        <div class="headerbox">
            <h1>Task List  </h1>
        <div class="headermenu">
            <?php include 'menu.php'; ?>
        </div>
    </div>
    <div class="tasklist">
        <table>
            <tr>
                <th>ID</th>
                <th>Creation Date</th>
                <th>Completion Date</th>
                <th>Task</th>
                <th>Status</th>
                <th>Action</th>
                <!-- <th style="width: 120px" style="text-align: center;">Action</th> -->
            </tr>
            <?php while ($task = mysqli_fetch_assoc($tasks)) { ?>
                <tr>
                    <td>
                        <?php echo $task['task_id']; ?>
                    </td>
                    <td>
                        <?php echo $task['created_at']; ?>
                    </td>
                    <td>
                        <?php echo $task['completed_at']; ?>
                    </td>
                    <td>
                        <?php echo $task['task']; ?>
                    </td>
                    <td>
                        <?php echo $task['status']; ?>
                    </td>
                    <td class="actionbtn">
                        <?php if ($task['status'] == 'incomplete') { ?>
                            <button class="btn edit"
                                onclick="window.location.href='edit.php?edit_task=<?php echo $task['task_id']; ?>'">Edit</button>
                            <button class="btn finish"
                                onclick="window.location.href='browse.php?fin_task=<?php echo $task['task_id']; ?>'">Finish</button>
                        <?php } ?>
                        <?php if ($task['status'] == 'completed') { ?>
                            <button class="btn edit"
                                onclick="window.location.href='edit.php?edit_task=<?php echo $task['task_id']; ?>'">Edit</button>
                            <button class="btn reopen"
                                onclick="window.location.href='browse.php?reopen_task=<?php echo $task['task_id']; ?>'">Reopen</button>
                        <?php } ?>
                        </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>

</html>