<?php
    session_start();
    include("connection.php");
    $user_id = $_SESSION['user_id']; // Fetch user_id from session

    $id = "";
    $title = "";
    $description = "";
    $due_date = "";

    $error = "";
    $success = "";

    if($_SERVER["REQUEST_METHOD"] == 'GET'){
        if(!isset($_GET['task_id'])){
            header("Location: index.php");
            exit;
        }
        
        $id = $_GET['task_id'];
        $sql = "SELECT * FROM tasks WHERE task_id='$id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        if(!$row){
            header("Location: index.php");
            exit;
        }

        $title = $row['title'];
        $description = $row['description'];
        $due_date = $row['due_date'];
    }
    else {
      $id = $_POST['task_id'];
      $title = isset($_POST['title']) ? $_POST['title'] : '';
      $description = isset($_POST['description']) ? $_POST['description'] : '';
      $due_date = isset($_POST['due_date']) ? $_POST['due_date'] : '';

        $sql = "UPDATE tasks SET title='$title', description='$description', due_date='$due_date' WHERE task_id='$id'";
        $result = $conn->query($sql);

        if ($result) {
            $success = "Task updated successfully.";
            header("Location: index.php");
        } else {
            $error = "Error updating task: " . $conn->error;
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
  <style>
    form.edit-task {
      border: 1px solid #ddd;
      padding: 20px;
      margin: auto;
      background-color: #f9f9f9;
      border-radius: 8px;
      width: 300px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;

    }

    .edit-task input[type="text"],
    .edit-task textarea,
    .edit-task input[type="date"] {
      width: 90%;
      padding: 8px;
      margin: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    .sidebar ul li a{
      background-color: #f5f5f5;
    }

    .edit-task .btn-submit {
      background-color: #4CAF50;
      color: white;
      padding: 10px 15px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .edit-task .btn-submit:hover {
      background-color: #45a049;
    }

    textarea {
      resize: none;
      height: 100px;
    }

    .btn-cancel {
      background-color: #f44336; /* red color */
      color: white; 
      border: none;
      padding: 10px 25px;
      text-align: center;
      cursor: pointer;
      border-radius: 5px;
      margin-top: 10px;
    }

    .btn-cancel:hover {
      background-color: #d32f2f; /* Hover effect*/
    }

    a:link, a:visited {
      background-color: white;
      color: black;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      margin-right: 5px;
    }
  </style>
</head>
<body>
  <!--Sidebar-->
  <aside class="sidebar">
    <ul>
      <li><a href="index.php">Home</a></li>
      <li><a href="calendar.php">Calendar</a></li>
      <li><a href="#">My Points</a></li>
      <li><a href="#">My Analysis</a></li>
    </ul>

    <form action="logout.php" method="post">
      <button type="submit" class="logout">Log out</button>
    </form>
  </aside>

  <div class="main-content">
    <header>
      <h1>Task Management</h1>
    </header>

    <section class="task-panel">
      <div class="task-header">
        <h2>Edit Task</h2>
        <a href="add_task_page.php">Add Task</a>
      </div>

      <div class="task-carousel">
        <div class="task-container">
          <div class="task-cards">
            <form class="edit-task" action="edit-task.php" method="post">
              <input type="hidden" name="task_id" value="<?php echo $id; ?>">
              <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" placeholder="Enter task title"><br>
              <textarea name="description" placeholder="Enter task description"><?php echo htmlspecialchars($description); ?></textarea><br>
              <input type="date" name="due_date" value="<?php echo htmlspecialchars($due_date); ?>"><br>
              <button type="submit" name="submit" class="btn-submit">Save Task</button>
              <button type="button" class="btn-cancel" onclick="window.location.href='index.php'">Cancel</button>
            </form>
          </div>
        </div>
      </div>
    </section>
  </div>
  <script src="script.js"></script>
</body>
</html>

