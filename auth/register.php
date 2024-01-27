<?php

namespace app;

session_start();

if (isset($_SESSION["user"])) {
    header("Location: ../admin/deshbord.php");
}

require_once "../app/database.php";

$obj = new Database();

if (isset($_POST["submit"])) {
    $firstName = $_POST["fname"];
    $listName = $_POST["lname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm-password"];
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $fullName = $firstName . " " . $listName;
    $userName = strtolower($firstName . $listName) . random_int(100000, 999999);
    $erors = []; 
    if (
        empty($firstName) or
        empty($listName) or
        empty($email) or
        empty($password) or
        empty($confirmPassword)
    ) {
        array_push($erors, "all filed requere");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($erors, "email is not valid");
    }

    if (strlen($password) < 8) {
        array_push($erors, "password must be 8 charactes");
    }

    if (strlen($password) < 8) {
        array_push($erors, "password must be 8 charactes");
    }

    if ($password !== $confirmPassword) {
        array_push($erors, "password does not match");
    }

    $emailsql = "SELECT * FROM `users` WHERE email ='$email'";

    if ($obj->emailValidate("users", $emailsql)) {
        array_push($erors, "Email Alradey Exjist");
    }
    $userName = "SELECT * FROM `users` WHERE username = '$userName'";
    if ($obj->userNameValidate("users", $userName)) {
        array_push($erors, "Username is alrady Exjiest");
    }

    if (count($erors) > 0) {
        foreach ($erors as $eror) {
            echo "<div class='alert'>$eror</div>";
        }
    } else {
        $userName =
            strtolower($firstName . $listName) . random_int(100000, 999999);
        

        $sqls = "INSERT INTO `users`(`fullname`, `username`, `email`, `password`) VALUES ('$fullName','$userName','$email',' $password_hash')";

        if ($obj->register("users", $sqls)) {
            session_start();
            $_SESSION["user"] = "yes";
            $_SESSION["data"] = $email;
            header("Location: ../admin/deshbord.php");
        } else {
            array_push($erors, "someting went rong");

            return false;
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>web app - register</title>
    <link rel="stylesheet" href="../public/register.css">
</head>
<body>
   <header></header>
   <main>
      <form action="" method="POST" class="register">
         <h3>Web App</h3>
         <div>
            <label for="fullname">Enter First Name:</label>
            <input type="text" name="fname" placeholder="Enter your first name...." min="6" max="50" required>
            <p></p>
         </div>
         <div>
            <label for="fullname">Enter Last Name:</label>
            <input type="text" name="lname" placeholder="Enter your last name...." min="6" max="50" required>
            <p></p>
         </div>
         <div>
            <label for="email">Enter Email:</label>
            <input type="email" name="email" placeholder="Enter your email...." min="6" max="50" required>
            <p></p>
         </div>
         <div>
            <label for="password">Enter Password:</label>
            <input type="password" name="password" placeholder="Enter your password...." min="8" required>
            <p></p>
         </div>
         <div>
            <label for="Confirm password">Confirm Password:</label>
            <input type="password" name="confirm-password" placeholder="Enter Re password...." min="8" required>
            <p></p>
         </div>
         <div>
            <button type="submit" name="submit">Register</button>
         </div>
      </form>
   </main>
   <footer></footer>
</body>
</html>