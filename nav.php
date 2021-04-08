<!-- Navigation bar-->
    <nav class="navbar navbar-inverse">
        <!-- Navbar content -->
        <div class="container-fluid">
            <div class="navbar-header">
                <i class="fas fa-ticket-alt logo"></i>
                <a class="navbar-brand" href="#">Ticket Support System</a>
            </div>
            <?php if(isset($_SESSION['loggedin'])) { ?>
                <form action="" method="POST" name="logout-form">
                    <input type="submit" name="logout-form_button" value="Logout" class="btn btn-primary navbar-right align-right">
                </form>
                <span class="navbar-text navbar-right">
                    <h4 class="navbar-username">Hello  <?php echo $_SESSION['username'] ?></h4>
                </span>
            <?php } else { ?>
                <a class="btn btn-primary navbar-right align-right" href="loginpage.php" name="login" role="button">Login</a>
            <?php } ?>
        </div>
    </nav>

<?php

    //Unset all the session varibles made.
    if(isset($_POST['logout-form_button'])) {

        session_start();
        unset($_SESSION['username']);
        unset($_SESSION['userid']);
        unset($_SESSION['usertype']);
        unset($_SESSION['loggedin']);
        header("Location: logout.php");

    }

?>