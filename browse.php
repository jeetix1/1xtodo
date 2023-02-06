<?php
// Connect to database
require_once '../../1xtodo-dbcon.php';

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    $task = mysqli_real_escape_string($conn, $_POST['task']);
    $id = $_POST['id'];
    $query = "UPDATE tasks SET task = '$task' WHERE id = $id";
    mysqli_query($conn, $query);
    header("Location: index.php");
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
    <h1>Task List</h1>
    <?php include 'menu.php'; ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Creation Date</th>
            <th>Completion Date</th>
            <th>Task</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($task = mysqli_fetch_assoc($tasks)) { ?>
            <tr>
                <td><?php echo $task['task_id']; ?></td>
                <td><?php echo $task['created_at']; ?></td>
                <td><?php echo $task['completed_at']; ?></td>
                <td><?php echo $task['task']; ?></td>
                <td><?php echo $task['status']; ?></td>
                <td>
                    <?php if ($task['status'] == 'incomplete') { ?>
                        <a href="edit.php?edit_task=<?php echo $task['task_id']; ?>">Edit</a>
                    <?php } ?>
                    <?php if ($task['status'] == 'completed') { ?>
                        <a href="index.php?reopen_task=<?php echo $task['task_id']; ?>">Reopen</a>
                    <?php } ?>

                    
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
