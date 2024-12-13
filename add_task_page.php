<?php
session_start();
include("connection.php");

// Check if the user logged in
if (!isset($_SESSION['user_id'])) {
    die("You need to be logged in to add tasks.");
}

$user_id = $_SESSION['user_id']; // Get the id from session

// Adding tasks to database
if (isset($_POST['submit'])) {
    $title = $_POST['task-title'];
    $description = $_POST['task-description'];
    $due_date = $_POST['due-date'];

    // SQL query to add tasks to the table
    $sql = "INSERT INTO tasks (title, description, due_date, user_id) VALUES ('$title', '$description', '$due_date', '$user_id')";

    // check if the query is successful
    if (mysqli_query($conn, $sql)) {
        // if it is successful direct it to index.php for displaying
        header("Location: index.php");
    } else {
        // if there is error print it on the screen
        echo "Error: " . mysqli_error($conn);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Task Management</title>
  <link rel="stylesheet" href="styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
  <style>
    .adding-task{
      height: 500px;
    }

    .task-card{
      height: 400px;
    }

    .wavy{
      text-decoration-style: wavy;
      text-decoration-line: underline;
      text-decoration-color: #FFCCEA;
    }

    .sidebar{
      background: #BFECFF;
    }

    body {
            font-family: "Quicksand", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
        }
        .btn-submit{
        background-color: #A7D477;
      }

      .btn-submit:hover {
        background-color: #6EC207;
      }

      .btn-cancel{
        background-color: #FF8080;
      }

      .btn-cancel:hover{
        background-color: #FA4032;
      }
  </style>
</head>
<body>
  <!--Sidebar-->
  <aside class="sidebar d-flex justify-content-center">
    <ul class="container">
      <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
      <li class="nav-item"><a class="nav-link active" href="calendar.php">Calendar</a></li>
      <li class="nav-item"><a class="nav-link active" href="completed-tasks.php">Completed Tasks</a></li>
      <li class="nav-item"><a class="nav-link active" href="analysis.php">My Analysis</a></li>
      <li class="nav-item">
      <form action="logout.php" method="post">
        <button type = "submit" class="logout btn btn-light">Log out</button>
      </form>
      </li>
  </ul>
  </aside>

  <div class="main-content">
    <header class="container-fluid">
      <h1 class="my-3 fw-bold">Task Management</h1>
    </header>
    <section class="task-panel container">
      <div class="task-header text-center mb-4">
        <h2 class="wavy fw-bold">Adding New Task</h2>
      </div>
      <div class="task-carousel mt-4">
        <div class="task-container">
          <div class="task-cards container ms-2 me-2">
          <form class="adding-task col" action="add_task_page.php" method="post">
                      <div class="task-card col-6 p-4">
                        <label for="task-title" class="task-title fw-bold w-100">Task title</label>
                        <input type="text" name="task-title" class="task-title form-control w-100" placeholder="Enter task title"><br>

                        <label for="task-description" class=" fw-bold w-100 mb-0">Task description</label>
                        <textarea name="task-description" class="task-description form-control w-100 mt-0" placeholder="Enter task description"></textarea><br>

                        <label for="due-date" class="due-date fw-bold w-100">Due Date</label>
                        <input type="date" name="due-date" class="task-due-date form-control w-100"><br>
                        <div class="grid gap-3 justify-content-center">
                          <button name="submit" class="btn-submit btn" type="submit">Save Task</button>
                          <button type="button" class="btn-cancel btn" onclick="window.location.href='index.php'">Cancel</button>
                        </div>
                      </div>
                    </form>  
          </div>
            
        </div>

      </div>
      
    </section>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="script.js"></script>
</body>
</html>