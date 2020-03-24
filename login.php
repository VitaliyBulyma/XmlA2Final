<?php
session_start();
date_default_timezone_set("America/Toronto");
// echo date("d M Y  h:i:s a"); 
$errMessage = $xmluser=$xmlpass=null;
if (isset($_POST['login'])) {
    //get values from form and assign to local variable
    $user = $_POST['uname'];
    $pass = $_POST['password'];

    // Load users xml file
    $xml = simplexml_load_file('xml/users.xml');

    // check username and password in every user in XML file
    foreach ($xml->user as $username) {

        if ($username->login->username == $user && $username->login->password == $pass) {

            $xmluser = $user;
            $xmlpass = $pass;
            $xmluserid = (int) $username['id'];
            $xmlusertype = (string) $username['type'];
        }
    } //echo $xmluid;

    // Display error if not matching Username and Password to any record
    //create a session if user exist
    if ($user == $xmluser && $pass == $xmlpass) {
        $_SESSION['uname'] = $xmluser;
        $_SESSION['password'] = $xmlpass;
        $_SESSION['xmluserid'] = $xmluserid;
        $_SESSION['xmlusertype'] = $xmlusertype;

        header('Location: secured.php');
    } else { 
      $errMessage= "Invalid name or password! Please, try Again";
    }
}
?>

<?php //session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <link rel="stylesheet" type="text/css" href="css/login.css">
  <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
  <script src="js/nav.js"></script>
  <title>Login</title>
</head>

<body onload="navSlide();">
  <!-- Navigation  -->
  <?php
  include_once 'nav.php';
  ?>
  <!-- end Navigation  -->


  <!-- login form -->
  <fieldset>
    <legend>
      <h1>Please Log In</h1>
    </legend>
    <form action="login.php" method="POST">
      <label for="uname">Username</label>
      <input type="text" id="uname" name="uname">

      <label for="password">Password</label>
      <input type="password" id="password" name="password">

      <input type="submit" name="login" value="Log in">
      <div id="errMessage">
        <?php echo $errMessage; ?>
      </div>
    </form>
  </fieldset>
  <!-- End login form -->


  <?php include  'footer.php'; ?>

</body>

</html>