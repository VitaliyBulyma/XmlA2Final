<?php
session_start();
unset($_SESSION['uname']);
unset($_SESSION['password']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <link rel="stylesheet" type="text/css" href="login.css">
  <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
  <script src="burger.js"></script>
  <title>Log Out</title>
</head>

<body onload="navSlide();">
<!-- Navigation  -->
<?php 
include_once 'nav.php';
?>
<!-- end Navigation  -->
    <?php
        echo "You have been successfully logged out!";
    ?>
    <footer>
        &copy<?php echo date("Y") . ' '; ?> <span>Vitaliy Bulyma</span>
    </footer>

</body>

</html>