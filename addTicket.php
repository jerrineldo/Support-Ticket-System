<?php

session_start();

require_once 'header.php';
require_once 'nav.php'; 

if(isset($_POST['addTicketForm_Add'])){

  $validticket = 1 ;

  $userid = $_SESSION['userid'];
  $subject = htmlspecialchars($_POST['addTicketForm_subject']);
  $category = htmlspecialchars($_POST['addTicketForm_category']);
  $message = htmlspecialchars($_POST['addTicketForm_message']);


  if($subject ==""){

    $subjecterr = "Subject cannot be empty";
    $validticket = 0 ;

  }

  if($message ==""){

    $messageerr = "Message cannot be empty";
    $validticket = 0 ;

  }

  if($validticket){

    $tickets = simplexml_load_file("XmlSheets/tickets.xml");
    $lastTicket = $tickets->xpath("/tickets/ticket[last()]");
    $lastticketnumber = (int)$lastTicket[0]->attributes()->number;

    $newnode = $tickets->addChild('ticket');
    $newnode->addAttribute('number',$lastticketnumber+1);
    $newnode->addchild('userId',$userid);
    $newnode->addchild('category',$category);
    $newnode->addchild('dateofIssue',date("Y-m-d\Th:i:s"));
    $newnode->addchild('subject',$subject);
    $newnode->addchild('status','open');

    $messageroot = $newnode->addchild('messages');

    $messagechild = $messageroot->addchild('message');

    $messagechild->addAttribute('userId',$userid);
    $messagechild->addAttribute('userType',$_SESSION['usertype']);

    $messagechild->addChild('dateTime',date("Y-m-d\Th:i:s"));
    $messagechild->addChild('description',$message);

    //Formatting the XML File
    $dom = new DOMDocument("1.0");
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($tickets->asXML());
    $dom->save("XmlSheets/tickets.xml");
  
    header("Location: ticketList.php");
  }
  
}

?>

<div class="addticket-container">
  <a href="ticketList.php" class="link-info">Back to List</a>
  <h2>Add New Ticket</h2>
  <div class="col-lg-6">
    <form name="addTicketForm" method="POST" action="">
      <div class="form-group col-md-8">
          <label for="addTicketForm_subject">Subject:</label>
          <input type="text" class="form-control" id="addTicketForm_subject" name="addTicketForm_subject"
            value="<?php echo isset($_POST['addTicketForm_subject'])?$_POST['addTicketForm_subject']:''; ?>" 
            aria-describedby="emailHelp" placeholder="Enter Subject"/>
      </div>
      <p class="loginform__errormessage">
                <?php echo isset($subjecterr)? $subjecterr:''; ?>
      </p>
      <div class="form-group col-md-8">
        <label for="addTicketForm_category">Category</label>
        <select class="form-control" name="addTicketForm_category" id="addTicketForm_category">
          <option>General</option>
          <option>Payment</option>
          <option>Shipping</option>
          <option>Returns</option>
          <option>Account</option>
        </select>
      </div>
      <div class="form-group col-md-8">
          <label>Message:</label>
          <textarea class="form-control" name="addTicketForm_message" id="addTicketForm_message" rows="3">
            <?php echo isset($_POST['addTicketForm_message'])?$_POST['addTicketForm_message']:''; ?>
          </textarea>
          <p class="loginform__errormessage">
                <?php echo isset($messageerr)? $messageerr:''; ?>
          </p>
      </div>
      <div class="form-group col-md-8">
        <input type="submit" class="button btn btn-primary" name="addTicketForm_Add" value="Add Ticket"/>
      </div>
    </form>
  </div>
</div>

<?php

require_once 'footer.php'; 

?>