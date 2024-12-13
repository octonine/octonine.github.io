<?php
    session_start();
    include("connection.php");

    // to make the completed task mark as uncompleted
if (isset($_GET['task_id'])) {
    $id = $_GET['task_id'];
    $sql = "UPDATE tasks SET is_completed = 0 WHERE task_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    
    // redirecting
    header("Location: completed-tasks.php");
    exit();
}
?>