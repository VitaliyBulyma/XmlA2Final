
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" type="text/css" href="login.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
    <script src="burger.js"></script>
    <title>Login</title>
</head>

<body onload="navSlide();">
<!-- Navigation  -->
    <?php
    include_once 'nav.php';
    ?>
<!-- end Navigation  -->
<!-- Main Content -->
    <?php
    session_start();
    date_default_timezone_set("America/Toronto");
        if (!isset($_SESSION['uname']) && !isset($_SESSION['password'])) {
            header('Location: login.php');
        }
        $xmlusers = simplexml_load_file('users.xml');
        $xmltickets = simplexml_load_file('supporttickets.xml');

        $x = $_SESSION['uname'];
        $y = $_SESSION['password'];
        $userfromsession=$_SESSION['xmlusertype'];
        $idfromget=(int)$_GET['ticketid'];
    
    // Render One Ticket  depending on ticket Id and user
        foreach ($xmlusers->user as $username) {

            //  render for support users  
            if ($username->login->username == $x && $username->login->password == $y && $username['type'] == "support") {
                // echo $username['type'];
                foreach ($xmltickets->suppticket as $suppticket) {
                    if ($suppticket->ticketid == (int)$idfromget) {
                        echo 'This line is for debugging NOT part of interface! User Role: '.$username['type'];
                        echo
                            '
                    <fieldset>
                        <form action="ticket.php?ticketid='. (int)$idfromget .'" method="POST">
                        <input type="hidden" name="ticketid" value="' . $suppticket->ticketid . '">
                            <select style="width: 40%;" name="statusInput" >
                                <option value="In-progress">In-Progress</option>
                                <option value="Resolved">Resolved</option>
                            </select>
                            <input style="width: 40%; height: 50% " type="submit"  name="statusChange" value="Change Status" />
                            <a class="right" href="secured.php">Close</a>                     
                        </form>
                        <form action="ticket.php?ticketid='. (int)$idfromget .'" method="POST">
                            <input type="hidden" name="ticketid" value="' . $suppticket->ticketid . '">
                            <legend>Ticket # ' . $suppticket->ticketid . '</legend>                        
                            <p> Status: ' . $suppticket->status . '</p>
                            <p> Date Created: ' . $suppticket->date . ' at: ' . $suppticket->time . ' ' . $suppticket->time["meridien"] . '</p>
                            <p> User ID: ' . $suppticket->userid .  '</p>
                            <p class="left">';
                                foreach ($suppticket->message as $message) {
                                    echo $message . '</br>';
                                }
                            
                             echo '
                                <input type="text" name="messinput" > </input>
                                <input type="submit" name="submit" value="Send Message" />
                        </form>       
                    </fieldset>
                ';
                    }
                }
            }


            // render for client users
            if ($username->login->username == $x && $username->login->password == $y && $username['type'] == "client") {
                $iduser = (int) $username['id'];
                foreach ($xmltickets->suppticket as $suppticket) {
                    if ($suppticket->ticketid == (int)$idfromget) {
                        echo 'This line is for debugging NOT part of interface! User Role: '.$username['type'];
                        echo
                            '
                    <fieldset>
                        <form action="ticket.php?ticketid='. (int)$idfromget .'" method="POST">
                            <input type="hidden" name="ticketid" value="' . $suppticket->ticketid . '">
                            <legend>Ticket # ' . $suppticket->ticketid . '<a class="right" href="secured.php">Close</a></legend> 
                            <p> Status: ' . $suppticket->status . '</p>
                            <p> Date Created: ' . $suppticket->date . ' at: ' . $suppticket->time . ' ' . $suppticket->time["meridien"] . '</p>
                            <p> User ID: ' . $suppticket->userid . '</p>
                            <p class="left">';
                        foreach ($suppticket->message as $message) {
                            echo  $message . '</br>';
                        }
                     
                        echo '</p></br>
                            <input type="text" name="messinput" ></input>
                            <input type="submit" name="submit" value="Send Message" />
                        </form>       
                    </fieldset>
                ';
                    }
                }
            }
        }
    // End Render all Tickets in system depending on user type
        
    // Send Message to XML file depending on user type
        if (isset($_POST['submit'])) {

            $input =  $_POST['messinput'];
            $ticketid = $_POST['ticketid'];

            $doc = new DOMDocument('1.0', "utf-8");
            $doc->preserveWhiteSpace = false;
            $doc->formatOutput = true;

            $supporttickets = simplexml_load_file("supporttickets.xml");
            // $root = $doc->documentElement;
            //var_dump($root);

            $ticket = null;
            foreach ($supporttickets as $t) {
                if ($ticketid == $t->ticketid) {
                    $ticket = $t;
                    break;
                }
            }

            $ticket->addChild("message",($userfromsession.": ".$input. " at ". date("h:i:s a d M y")));
            // $ticket->addChild($_SESSION['xmlusertype'] == "support" ? "adminmessage" : "usermessage",($input. " at ". date("h:i:s a d M y")));
            $supporttickets->asXML("supporttickets.xml");
            header("Refresh:0.5");
        }
    // End Send Message to XML file depending on user type

    // Status Change
        if (isset($_POST['statusChange'])) {

            $statusinput =  $_POST['statusInput'];

            $ticketid = $_POST['ticketid'];

            $doc = new DOMDocument('1.0', "utf-8");
            $doc->preserveWhiteSpace = false;
            $doc->formatOutput = true;

            $supporttickets = simplexml_load_file("supporttickets.xml");


            $ticket = null;
            foreach ($supporttickets as $t) {
                if ($ticketid == $t->ticketid) {
                    $ticket = $t;
                    break;
                }
            }

            $ticket->status= $statusinput;
            $supporttickets->asXML("supporttickets.xml");
            header("Refresh:0");
        }
    // End Status Change


        ?>

<!-- End Main Content -->
<!-- Footer -->
    <?php include_once 'footer.php'; ?>
<!-- End Footer -->
</body>

</html>