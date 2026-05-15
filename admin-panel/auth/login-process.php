<?php

session_start();

$host = "localhost";
$dbname = "mini_ecommerce";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

if($conn->connect_error){
    die("Connection Failed : " . $conn->connect_error);
}

if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validation

    if(empty($email) || empty($password)){

        $_SESSION['error'] = "All fields are required";
        header("Location: login.php");
        exit;
    }

    // Check Admin

    $sql = "SELECT * FROM admins WHERE email = ? LIMIT 1";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows > 0){

        $admin = $result->fetch_assoc();

        // Password Verify

        if($password == $admin['password']){

            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];

            $_SESSION['success'] = "Login Successful";

            header("Location: ../dashboard/index.php");
            exit;

        }else{

            $_SESSION['error'] = "Invalid Password";
            header("Location: login.php");
            exit;
        }

    }else{

        $_SESSION['error'] = "Admin Not Found";
        header("Location: login.php");
        exit;
    }

}
?>