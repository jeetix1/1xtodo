<?php
// Connect to database
require_once '../../1xtodo-dbcon.php';

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check if the edit task button has been clicked
if (isset($_GET['edit_task'])) {
    $id = $_GET['edit_task'];
    $query = "SELECT task FROM tasks WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $task = mysqli_fetch_assoc($result);
}

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    $task = mysqli_real_escape_string($conn, $_POST['task']);
    $id = $_POST['id'];
    $query = "UPDATE tasks SET task = '$task' WHERE id = $id";
    mysqli_query($conn, $query);
    $log_event = "Task edited: " . $task;
    $query = "INSERT INTO log (task_id, event) VALUES ($id, '$log_event')";
    mysqli_query($conn, $query);
    header("Location: index.php");
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
    <!-- Include the TinyMCE library -->
    <script src="https://cdn.tiny.cloud/1/qjavekyfrmwellhujyp3l9wlss8g6rdxgqyamfkcgxrmmmd2/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <!-- Initialize the TinyMCE editor -->
    <script>
        tinymce.init({
            selector: 'textarea',  // Select the textarea element to apply the editor to
            plugins: 'link code',  // Add the link and code plugins to enable adding links and code snippets
            toolbar: 'bold italic underline strikethrough | link | alignleft aligncenter alignright alignjustify | checklist numlist bullist indent outdent | forecolor backcolor | removeformat',  // Customize the toolbar to include bold, italic, underline, and link buttons
            menubar: false  // Hide the menu bar
        });
    </script>
</head>
<body>
    <h1>Task List</h1>
    <button class="btn default" onclick="window.location.href='browse.php'">Browse all</button>
    <?php if (isset($_GET['edit_task'])) { ?>
        <form action="edit.php" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <!-- Add the class to the textarea -->
            <textarea class="tinymce" name="task" rows="30" cols="50"><?php echo $task['task']; ?></textarea>
            <input type="submit" name="submit" value="Save Task">
        </form>

         <!-- Add the code for listing log events related to the task -->
         <h2>Log Events</h2>
        <table>
            <tr>
                <th>Event</th>
                <th>Date</th>
            </tr>
            <?php 
                $query = "SELECT * FROM log WHERE task_id = $id";
                $logs = mysqli_query($conn, $query);
                while ($log = mysqli_fetch_assoc($logs)) { ?>
                <tr>
                    <td><?php echo $log['event']; ?></td>
                    <td><?php echo $log['timestamp']; ?></td>
                </tr>
            <?php } ?>
        </table>
    <?php } else { ?>
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
                            <a href="edit.php?edit_task=<?php echo $task['id']; ?>">Edit</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
        
    <?php } ?>
</body>
</html>




