<?php

session_start();
$html = "";

if($_SESSION['usertype'] == 'admin'){
  
  $tickets = simplexml_load_file("XmlSheets/tickets.xml");

  foreach($tickets->children() as $ticket) {

    $html .= '<tr>';
    $html .= "<td>
                <form action = 'ticketDetails.php' method='GET'>
                  <input type='hidden' name = 'id' value=".$ticket['number'].">
                  <input type='submit' value='".$ticket['number']."'>
                </form>
              </td>";
    $html .= '<td>'.$ticket->userId.'</td>';
    $html .= '<td>'.$ticket->category.'</td>';

    $date = $ticket->dateofIssue;
    $datetoDisplay = date_create_from_format('Y-m-d\TH:i:s',$date)->format('Y-m-d H:i:s');
    
    $html .= '<td>'.$datetoDisplay.'</td>';
    $html .= '<td>'.$ticket->subject.'</td>';
    $html .= '<td>'.$ticket->status.'</td>';
    $html .= '</tr>';

  }

} elseif($_SESSION['usertype'] == 'client'){

  $tickets = simplexml_load_file("XmlSheets/tickets.xml");

  foreach($tickets->children() as $ticket) {

   if($ticket->userId==$_SESSION['userid']) {

    $html .= '<tr>';
    $html .= "<td>
                <form action = 'ticketDetails.php' method='GET'>
                  <input type='hidden' name = 'id' value=".$ticket['number'].">
                  <input type='submit' value='".$ticket['number']."'>
                </form>
              </td>";
    $html .= '<td>'.$ticket->userId.'</td>';
    $html .= '<td>'.$ticket->category.'</td>';

    $date = $ticket->dateofIssue;
    $datetoDisplay = date_create_from_format('Y-m-d\TH:i:s',$date)->format('Y-m-d H:i:s');
    
    $html .= '<td>'.$datetoDisplay.'</td>';
    $html .= '<td>'.$ticket->subject.'</td>';
    $html .= '<td>'.$ticket->status.'</td>';
    $html .= '</tr>';
   }

  }

}

function addButton(){

  $button = '<form>
              <input type="submit" class = "button btn" value="Details"/>
            </form>';
  return $button;

}

?>
<?php 

require_once 'header.php';
require_once 'nav.php'; 

?>
<style>

<?php 
require_once 'css/style.css' 
?>

</style>

<div class="tickets-container">
    <table class="table">
      <thead>
        <tr>
          <th class="center" scope="col">Ticket Number</th>
          <th class="center" scope="col">User Id</th>
          <th class="center" scope="col">Category</th>
          <th class="center" scope="col">Date of Issue</th>
          <th class="center" scope="col">Subject</th>
          <th class="center" scope="col">Status</th>
        </tr>
      </thead>
      <tbody>
        <? print $html; ?>
      </tbody>
    </table>
    <a class="btn btn-primary" href="addTicket.php" role="button">Add Ticket</a>
</div>

<?php

    require_once 'footer.php'; 

?>