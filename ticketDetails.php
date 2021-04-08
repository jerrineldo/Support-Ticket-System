
<?php

session_start();
require_once 'header.php';
require_once 'nav.php'; 

$id = $_GET['id'];
$tickets = simplexml_load_file("XmlSheets/tickets.xml");

$selectedticket_dateofIssue = "";
$selectedticket_category = "";
$outputmessages = "";
foreach($tickets->children() as $ticket) {
   
    if($ticket['number'] == $id) {

        $selectedticket_dateofIssue = date_create_from_format('Y-m-d\TH:i:s',$ticket->dateofIssue)->format('Y-m-d H:i:s');
        $selectedticket_category = $ticket->category;
        $selectedticket_status = $ticket->status;
        $count = 1;
        foreach($ticket->messages->message as $message){

            $outputmessages .= '<div class="form-group col-md-8">
                                    <label class="label label-default" for="ticket-update_message'.$count.'">
                                        User ID:'.$message['userId'].' 
                                        Posted: '.date_create_from_format('Y-m-d\TH:i:s',$message->dateTime)->format('Y-m-d H:i:s').'
                                    </label>
                                    <input ';

            if($_SESSION['usertype']!="admin") {

                $outputmessages .= "disabled ";

            }

            $outputmessages .= 'type="text" class="form-control" id="ticket-update_message'.$count.'" name="" placeholder="" value="'.$message->description.'">
                               </div>';

            $count = $count + 1;
        }
    }

}

if(isset($_POST['Send'])){

    $message = htmlspecialchars($_POST['newmessage']);

    if($message == ""){

        $messageerr = "Message cannot be empty";

    }
    else {
        foreach($tickets->children() as $ticket) {
   
            if($ticket['number'] == $id) {

                $messageroot = $ticket->messages;
                $messagenode = $messageroot->addchild('message');

                $messagenode->addAttribute('userId',$_SESSION['userid']);
                $messagenode->addAttribute('userType',$_SESSION['usertype']);
                $messagenode->addchild('dateTime',date("Y-m-d\Th:i:s"));
                $messagenode->addchild('description',$message);

                $dom = new DOMDocument("1.0");
                $dom->preserveWhiteSpace = false;
                $dom->formatOutput = true;
                $dom->loadXML($tickets->asXML());
                $dom->save("XmlSheets/tickets.xml");

                header("Location: ticketDetails.php?id=$id");

            }
        }
    }
}

?>

  <div class="ticketdetails-container">
    <h2>Ticket Details</h2>
    <a href="ticketList.php" class="link-info">Back to List</a>
    <h4>Ticket #<?= $id ?></h4>
    <form method="POST" name="ticket-update" action="">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label class="label label-default" for="ticket-update_dateopened">Date Opened:</label>
                <input <?php if($_SESSION['usertype']!="admin") echo "disabled "; ?> type="text" class="form-control" id="ticket-update_dateopened" name="dateopened" placeholder="" value="<?=$selectedticket_dateofIssue?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label class="label label-default" for="ticket-update_status">Status:</label>
                <input <?php if($_SESSION['usertype']!="admin") echo "disabled "; ?> type="text" class="form-control" id="ticket-update_status" name="dateopened" placeholder="" value="<?=$selectedticket_status?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label class="label label-default" for="ticket-update_category">Category:</label>
                <input <?php if($_SESSION['usertype']!="admin") echo "disabled "; ?>type="text" class="form-control" id="ticket-update_category" name="category" placeholder="" value="<?=$selectedticket_category?>">
            </div>
        </div>
        <div class="form-row">
            <?= $outputmessages ?>
        </div>
        <div class="form-row">
            <div class="form-group col-md-8">
                <label class="label-default" for="ticket-update_category">Post a message</label>
                <input type="text" class="form-control" id="ticket-update_newmessage" name="newmessage" placeholder="Reply.." value="">
                <p class="loginform__errormessage">
                <?= isset($messageerr)?$messageerr:''; ?></p>
            </div>
        </div>
        <div class="form-row margindown">
            <div class="form-group col-md-8 ">
                <input type="submit" class="btn btn-success col-md-4" value="Send Message" name="Send">
            </div>
        </div>
    </form>
  </div>

<?php

require_once 'footer.php'; 

?>