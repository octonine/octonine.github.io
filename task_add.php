
<?php
  session_start();
  include("connection.php");

  ini_set('display_errors', 1);
  error_reporting(E_ALL);

  // checking the form data
  if (isset($_POST['submit'])) {
    $title = $_POST['task-title'];
    $description = $_POST['task-description'];
    $due_date = $_POST['due-date'];

    // adding task to the database
    $sql = "INSERT INTO tasks (title, description, due_date) VALUES ('$title', '$description', '$due_date')";
    
    // checking whether the query is successful or not
    if (mysqli_query($conn, $sql)) {
      // if it is successful direct to the main page
      header("Location: index.php");
      exit();
    } else {
      // if there is error print on the screen
      echo "Error: " . mysqli_error($conn);
    }
  }
?>




