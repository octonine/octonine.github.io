<?php
include("connection.php");
ini_set('display_errors', 1);
error_reporting(E_ALL);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // get data from form
    $username = mysqli_real_escape_string($conn, $_POST['register-username']);
    $password = mysqli_real_escape_string($conn, $_POST['register-password']);
    $email = mysqli_real_escape_string($conn, $_POST['register-email']);
    
    // checking usernamme
    $check_username_sql = "SELECT * FROM users WHERE username = '$username'";
    $check_username_result = mysqli_query($conn, $check_username_sql);
    
    // checking email
    $check_email_sql = "SELECT * FROM users WHERE email = '$email'";
    $check_email_result = mysqli_query($conn, $check_email_sql);

    // checking if the username already exists
    if (mysqli_num_rows($check_username_result) > 0) {
        // If username exists, return an error
        echo json_encode([
            'status' => 'error',
            'error' => 'username' // we show the type of error
        ]);
        exit();
    }
    
    // checking if email already exists
    if (mysqli_num_rows($check_email_result) > 0) {
        // If email exists, return an error
        echo json_encode([
            'status' => 'error',
            'error' => 'email' // type of error
        ]);
        exit();
    }

    // if both are unique, add user to the database
    $sql = "INSERT INTO users (username, pword, email) VALUES ('$username', '$password', '$email')";
    if (mysqli_query($conn, $sql)) {
        echo json_encode([
            'status' => 'success'
        ]);
    } else {
        // If the insert query fails, return a general error
        echo json_encode([
            'status' => 'error',
            'error' => 'general' // general error
        ]);
    }
}
?>

