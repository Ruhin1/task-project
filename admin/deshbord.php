<?php

namespace admin;

use app\Database;

session_start();

if(!isset($_SESSION['user'])){
  header("Location: ../auth/login.php");
}

require_once '../app/database.php';
$obj = new Database();

if($_SESSION['data']){
    $email = $_SESSION['data'];
    $data = $obj->auth($email);
}

require_once '../app/database.php';
$obj = new Database();
if(isset($_POST['logout'])){
    $obj->logout();
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>web app - deshbord</title>
    <!-- css here -->
    <link rel="stylesheet" href="../public/deshbord.css">
</head>
<body>
   <header class="header">
      <div>
         <h3>Web App</h3>
      </div>
      <div>
         <form action="" method="POST">
            <button type="submit" name="logout">Logout</button>
         </form>
      </div>
   </header>
   <main>
      <div class="content">
         <h3>Full Name: <?php echo $data['fullname']?> </h3>
         <br/>
         <h3>User Name: <?php echo $data['username']?> </h3>
         <br/>
         <h3>Email: <?php echo $data['email']?> </h3>
         <br/>
      </div>
   </main>
   <footer></footer>
</body>
</html>