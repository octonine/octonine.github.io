<?php
    session_start();
    include("connection.php");
    
    if(isset($_GET['task_id'])){
        $id = $_GET['task_id'];
        $sql = "DELETE FROM `tasks` where task_id=$id";
        $conn->query($sql);
    }
    header("Location: index.php");
    exit;

?>
