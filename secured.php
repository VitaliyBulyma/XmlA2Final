<?php
session_start();
date_default_timezone_set("America/Toronto");
if(!isset($_SESSION['uname']) && !isset($_SESSION['password'] )){
    header('Location: login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
    <script src="js/nav.js"></script>
    <title>Secured Content</title>
</head>

<body onload="navSlide();">
    <!-- Navigation  -->
    <?php
    include_once 'nav.php';
    ?>
    <!-- end Navigation  -->
    <?php

    // if (!isset($_SESSION['uname']) && !isset($_SESSION['password'])) {
    //     header('Location: login.php');
    // }
    $xmlusers = simplexml_load_file('xml/users.xml');
    $xmltickets = simplexml_load_file('xml/supporttickets.xml');

    $x = $_SESSION['uname'];
    $y = $_SESSION['password'];


// Render all Tickets in system depending on user type
    foreach ($xmlusers->user as $username) {

        //  render for support users  
        if ($username->login->username == $x && $username->login->password == $y && $username['type'] == "Support") {
            //echo 'This line is for debugging NOT part of interface! User Role: '.$username['type'];
            foreach ($xmltickets->suppticket as $suppticket) {
                if ($suppticket) {
                    echo
                        '
                <fieldset>
                    <form action="secured.php" method="POST">
                        <input type="hidden" name="ticketid" value="' . $suppticket->ticketid . '">
                        <legend>Ticket # ' . $suppticket->ticketid . '<a class="right" href="ticket.php?ticketid='.$suppticket->ticketid.'">View</a></legend>                        
                        <p> Status: ' . $suppticket->status . '</p>
                        <p> Date Created: ' . $suppticket->date . ' at: ' . $suppticket->time . ' ' . $suppticket->time["meridien"] . '</p>
                        <p> User ID: ' . $suppticket->userid . '</p>

                    </form>       
                </fieldset>
            ';
                }
            }
        }


        // render for client users
        if ($username->login->username == $x && $username->login->password == $y && $username['type'] == "Client") {
            $iduser = (int) $username['id'];
            //echo 'This line is for debugging NOT part of interface! User Role: '.$username['type'];
            foreach ($xmltickets->suppticket as $suppticket) {
                if ($suppticket->userid == $iduser) {
                    echo
                        '
                <fieldset>
                    <form action="secured.php" method="POST">
                        <input type="hidden" name="ticketid" value="' . $suppticket->ticketid . '">
                        <legend>Ticket # ' . $suppticket->ticketid . '<a class="right" href="ticket.php?ticketid='.$suppticket->ticketid.'">View</a></legend> 
                        <p> Status: ' . $suppticket->status . '</p>
                        <p> Date Created: ' . $suppticket->date . ' at: ' . $suppticket->time . ' ' . $suppticket->time["meridien"] . '</p>
                        <p> User ID: ' . $suppticket->userid . '</p>

                    </form>       
                </fieldset>
            ';
                }
            }
        }
    }
// End Render all Tickets in system depending on user type

    // if (isset($_POST['submit'])) {

    //     $input =  $_POST['messinput'];
    //     $ticketid = $_POST['ticketid'];

    //     $doc = new DOMDocument('1.0', "utf-8");
    //     $doc->preserveWhiteSpace = false;
    //     $doc->formatOutput = true;

    //     $supporttickets = simplexml_load_file("supporttickets.xml");
     
    //     $ticket = null;
    //     foreach ($supporttickets as $t) {
    //         if ($ticketid == $t->ticketid) {
    //             $ticket = $t;
    //             break;
    //         }
    //     }



    //     $ticket->addChild($_SESSION['xmlusertype'] == "support" ? "adminmessage" : "usermessage",($input. " at ". date("h:i:s a d M y")));
    //     $supporttickets->asXML("supporttickets.xml");
    // }
    ?>
    <?php include_once 'footer.php'; ?>
</body>

</html>