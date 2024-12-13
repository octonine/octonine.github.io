<?php
    session_start(); // starting login
    include("connection.php");

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = '$username' AND pword = '$password'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $row['user_id']; 
            header("Location: index.php"); 
            exit();
        } else {
            // when the login is failed, add parameter 
            header("Location: login-page.php?login_error=true");
            exit();
        }
    }  
?>
