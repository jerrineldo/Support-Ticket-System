<?php 

if(isset($_POST['Login'])){

    $username = htmlspecialchars($_POST['login-form__username']);
    $password = htmlspecialchars($_POST['login-form__password']);

    $usernamepattern = "/^[A-Za-z0-9_]{3,16}$/";
    $passwordpattern = "/^[A-Za-z0-9_]{3,16}$/";

    //Variable to check if credentials are Valid
    $invalidcredentials = 0;

    if($username == "") {

        $usernameerr = "Username cannot be empty";
        $invalidcredentials = 1;

    } else if(!preg_match($usernamepattern,$username)){
        
        $usernameerr = "Username INVALID";
        $invalidcredentials = 1;

    }

    if($password == "") {

        $passworderr = "Password cannot be empty";
        $invalidcredentials = 1;

    } else if(!preg_match($passwordpattern,$password)){
        
        $passworderr = "Password INVALID";
        $invalidcredentials = 1;

    }

    $validlogin = 0;

    if($invalidcredentials == 0) {

        $users = simplexml_load_file("XmlSheets/users.xml");
        
        foreach($users->children() as $user) {

            if($user->userName == $username && hash('md5', $password) == $user->password) {

                //SET the session varibles
                $_SESSION['username'] = $username;
                $_SESSION['userid'] = $user->userId->__tostring();
                $_SESSION['usertype'] = $user['userType']->__tostring();
                $_SESSION['loggedin'] = 1;

                header("Location: ticketList.php");
                $validlogin = 1;
                break;

            }
            else {
                
            }
        }

    }

    if($validlogin == 0 && $invalidcredentials == 0){

        $loginerr = "Invalid credentials";

    }
    
}//end of if(isset($_POST['Login']))

?>

<div class="main-loginpage">
    <form action="" method="POST" name="login-form" class="login-form">
        <h1 class="login-form__mainheading">Login</h1>
        <span hidden class="login-form__errormessages">Invalid Credentials</span>
        <div class="login-form__inputfields">
            <label hidden>Username</label>
            <span>
                <i class="fas fa-user"></i>
            </span>
            <input type="text" placeholder="Username" id="login-form__username" 
            value="<?php echo isset($_POST['login-form__username'])?$_POST['login-form__username']:''; ?>" autocomplete="off" name="login-form__username"/>
            <p class="loginform__errormessage">
                <?php echo isset($usernameerr)?$usernameerr:''; ?>
            </p>
            <hr/>
        </div>
        <div class="login-form__inputfields">
            <span>
                <i class="fas fa-user"></i>
            </span>
            <label hidden>Password</label>
            <input type="password" placeholder="Password" id="login-form__password" 
            value="<?php echo isset($_POST['login-form__password'])?$_POST['login-form__password']:''; ?>" name="login-form__password"/>
            <p class="loginform__errormessage">
                <?php echo isset($passworderr)?$passworderr:''; ?>
            </p>
            <hr/>
            <p class="loginform__errormessage">
                <?php echo isset($loginerr)?$loginerr:''; ?>
            </p>
        </div>
        <div>
            <input type="submit" value="Login" class="login-form__submitbutton" name="Login"/>
        </div>
        <div class= "login-form__createLink">
            <a href="#" class="login-form__createAccount">Dont have an Account? Join Now</a>
        </div>   
    </form>
</div>