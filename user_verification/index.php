<?php 
require_once 'controllers/authController.php';
/*
//verify the user using token|email link
if(isset($_GET['token'])){
    $token = $_GET['token'];
    verifyUserToken($token);
}
*/
if(isset($_GET['password_token'])){
    $passwordToken = $_GET['password_token'];
    resetPasswordToken($passwordToken);
}
if(!isset($_SESSION['id'])){
    header('location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

    <link rel="stylesheet" href="style.css">

    <title>Homepage</title>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-4 offset-md-4 form-div login">

            <!--Any|alert message field-->
            <?php if(isset($_SESSION['message'])): ?>
            <div class="alert <?php echo $_SESSION['alert-class'];?>">
                <?php
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                    unset($_SESSION['alert-class']);
                ?>
            </div>
            <?php endif; ?>

            <h3>Welcome, <?php echo $_SESSION['username']; ?></h3>
            <a href="login.php?logout=1" class="logout">Logout</a><br><br>
               
            <?php if(!$_SESSION['verified']): ?>
                <div class="alert alert-warning">
                    <p>You need to verify your account. <br>
                    Sign in to your email account and click on the verification link we just emaled you at <strong><?php echo $_SESSION['email']; ?></strong></p>
                    <form method="post">
                        <!--Code resend mail-->
                        <br>  
                        <input type="text" name="code" placeholder="Enter your Code here"><br>
                        <br>
                        <button type="submit" name="verify-btn" class="btn btn-primary btn-block btn-lg">Verify</button>
                        <br>
                        <p>In case the code is not working,<br> press the button below to resend it:</p>
                        <button type="submit" name="resend-code-btn" class="btn btn-primary btn-block btn-lg">Resend code</button>
                        <!-- Link resend mail 
                        <p>In case the link is not working,<br> press the button below to resend it:</p>
                        <button type="submit" name="resend-link-btn" class="btn btn-primary btn-block btn-lg">Resend link</button> -->
                    </form>
                </div>
                <div id="mail_poslat"></div>
            <?php endif; ?>

            <?php if($_SESSION['verified']): ?>
                <div>
                    <p>Hello!<br>
                    You have successfully verified your account via email: "<?php echo $_SESSION['email']; ?>" .</p>
                </div>
                <!--some additional settings for verified users below?-->
            <?php endif; ?>
        </div>
    </div>
</div>
    
</body>
</html>