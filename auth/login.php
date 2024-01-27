<?php
namespace app;

session_start();

if (isset($_SESSION["user"])) {
    header("Location: ../admin/deshbord.php");
}

require_once "../app/database.php"; 

$obj = new Database();

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $sql = "SELECT * FROM `users` WHERE email = '$email'";
    if ($obj->login("users", $sql, $password)) {
       return true;
    }else{
       echo "<div class='alert'>";
       echo "the credential do not match our own record";
       echo "</div>";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>web app - login</title>
    <link rel="stylesheet" href="../public/login.css">
</head>
<body>
   <header></header>
   <main>
      <form action="" method="POST" class="login">
         <h3>Web App</h3>
         <div>
            <label for="email">Enter Email:</label>
            <input type="email" name="email" placeholder="Enter your email...." required>
            <p></p>
         </div>
         <div>
            <label for="password">Enter Password:</label>
            <input type="password" name="password" placeholder="Enter your password...." required>
            <p></p>
         </div>
         <div>
            <button type="submit" name="login">Login</button>
         </div>
      </form>
   </main>
   <footer></footer>
</body>
</html>