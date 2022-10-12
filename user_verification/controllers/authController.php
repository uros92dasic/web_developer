<?php
session_start();

require 'config/db.php';
require_once 'emailController.php';

$errors = array();
$username = "";
$email = "";

//if user clicks on the sign up button
if(isset($_POST['signup-btn'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordConf = $_POST['passwordConf'];

    //sign-up info validation
    if(!preg_match('/^[a-zA-Z0-9]+$/', $username)){
        $errors['username']="Please enter a valid username.";
    }
    //unique username validation
    $usernameQuery = "SELECT * FROM users WHERE username=? LIMIT 1";
    $stmt = $conn->prepare($usernameQuery);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $userCount = $result->num_rows;
    $stmt->close();
    if($userCount > 0){
        $errors['username']="Username already exists.";
    }
    if(empty($username)){
        $errors['username']="Username required.";
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors['email']="Email address is invalid.";
    }
    //unique email validation
    $emailQuery = "SELECT * FROM users WHERE email=? LIMIT 1";
    $stmt = $conn->prepare($emailQuery);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $userCount = $result->num_rows;
    $stmt->close();
    if($userCount > 0){
        $errors['email']="Email already exists.";
    }
    if(empty($email)){
        $errors['email']="Email required.";
    }

    if(empty($password)){
        $errors['password']="Password required.";
    }

    if($password !== $passwordConf){
        $errors['password']="The two passwords do not match.";
    }

    if(count($errors) === 0){
        //password encryption
        $password = password_hash($password, PASSWORD_DEFAULT);
        //token
        $token = bin2hex(random_bytes(50));
        $verified = false;

        $sql = "INSERT INTO users (username, email, verified, token, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssbss', $username, $email, $verified, $token, $password);
        if($stmt->execute()){
            //login user
            $user_id=$conn->insert_id;
            $_SESSION['id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['token'] = $token;
            $_SESSION['verified'] = $verified;

            /*
            //verification email using link
            $recipient = $email;
            sendVerificationEmail($recipient, $token);
            */

            //verification email using code
            $code = rand(100000,999999);
            $expires = (time() + (60 * 10));
            
            $sql = "INSERT INTO verify (code, expires, email) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iis', $code, $expires, $email);
            if($stmt->execute()){ 
                $recipient = $email;
                $message = "Your code is " . $code;
                $subject = "Email verification";
                send_mail($recipient,$subject,$message);  
            }

            //set flash message
            $_SESSION['message'] = "You are now logged in!";
            $_SESSION['alert-class']= "alert-success";
            header('location: index.php');
            exit();
        }
        else{
            $errors['db_error']="Database error: failed to register.";
        }
    }
}

//if user clicks on the login button
if(isset($_POST['login-btn'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    //validation
    if(empty($username)){
        $errors['username']="Username required.";
    }
    if(empty($password)){
        $errors['password']="Password required.";
    }

    if(count($errors)===0){
        $sql = "SELECT * FROM users WHERE email=? OR username=? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    
        if($user != null && password_verify($password, $user['password'])){
            //login success
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['verified'] = $user['verified'];
            //set flash message
            $_SESSION['message'] = "You are now logged in!";
            $_SESSION['alert-class']= "alert-success";
            header('location: index.php');
            exit();
        }   
        else{
            $errors['login_fail'] = "Wrong credentials.";
        }
    }
}

//logout user
if(isset($_GET['logout'])){
    session_destroy();
    //session_unset();
    unset($_SESSION['id']);
    unset($_SESSION['username']);
    unset($_SESSION['email']);
    unset($_SESSION['verified']);
    header('location: login.php');
    exit();
}

//if user clicks on the verify button
if(isset($_POST['verify-btn'])){
    $email=$_SESSION['email'];
    $code=$_POST['code'];

    $sql = "SELECT * FROM verify WHERE email='$email' AND code='$code'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $userCount = $result->num_rows;
    $verify = mysqli_fetch_assoc($result);
    $time = time();

    if($userCount > 0){
        if($verify['expires'] > $time){
            verifyUser($email);
        }
        else{
            $_SESSION['message'] = "Code expired...";
            $_SESSION['alert-class']= "alert-warning";
        }
    }
    else{
        $_SESSION['message'] = "Wrong code.";
        $_SESSION['alert-class']= "alert-danger";
    }
}

//if user clicks on the resend code button
if(isset($_POST['resend-code-btn'])){
    $email=$_SESSION['email'];
    $code = rand(100000,999999);
    $expires = (time() + (60 * 10));

    $result=check_code($email);

    if($result->num_rows>0){
        update_old_code($email, $code);
    }
    else{
        insert_new_code($email, $code, $expires);
    }
}

function verifyUser($email){
    global $conn;
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result)>0){
        $user = mysqli_fetch_assoc($result);
        $update_query = "UPDATE users SET verified=1 WHERE email='$email'";

        if(mysqli_query($conn, $update_query)){
            //log user in
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['verified'] = 1;
            //set flash message
            $_SESSION['message'] = "Your email address was succesfully verified!";
            $_SESSION['alert-class']= "alert-success";
            header('location: index.php');
            exit();
        }
    }
    else{
        echo 'User not found!';
    }
}

function check_code($email){
    global $conn;
    $sql = "SELECT * FROM verify WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    return $result;
}

function update_old_code($email, $code){
    global $conn;
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result)>0){
        $user = mysqli_fetch_assoc($result);
        $update_query = "UPDATE verify SET code='$code' WHERE email='$email'";
        mysqli_query($conn, $update_query);
        $message = "Your new code is " . $code;
        $subject = "Email verification";
        $recipient = $email;
        send_mail($recipient,$subject,$message);
    }
    else{
        echo 'User not found!';
    }
}

function insert_new_code($email, $code, $expires){
    global $conn;
    $sql = "INSERT INTO verify (code, expires, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iis', $code, $expires, $email);
    if($stmt->execute()){ 
        $message = "Your code is " . $code;
        $subject = "Email verification";
        $recipient = $email;
        send_mail($recipient,$subject,$message);
    }
}
/*
//verify the user using token
function verifyUserToken($token){
    global $conn;
    $sql = "SELECT * FROM users WHERE token='$token' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result)>0){
        $user = mysqli_fetch_assoc($result);
        $update_query = "UPDATE users SET verified=1 WHERE token='$token'";
        if(mysqli_query($conn, $update_query)){
            //log user in
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['verified'] = 1;
            //set flash message
            $_SESSION['message'] = "Your email address was succesfully verified!";
            $_SESSION['alert-class']= "alert-success";
            header('location: index.php');
            exit();
        }
    }
    else{
        echo "User not found.";
    }
}
//if user clicks on the resend link button
if(isset($_POST['resend-link-btn'])){
    $email=$_SESSION['email'];
    global $conn;
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    $token = $user['token'];
    $email = $user['email'];
    $recipient = $email;
    sendVerificationEmail($recipient, $token);
}
*/
//if user clicks on the forgot password button
if(isset($_POST['forgot-password-btn'])){
    $email = $_POST['email'];

    $emailQuery = "SELECT * FROM users WHERE email=? LIMIT 1";
    $stmt = $conn->prepare($emailQuery);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $userCount = $result->num_rows;
    $stmt->close();
    if($userCount < 1){
        $errors['email']="Email does not exists.";
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors['email']="Email address is invalid.";
    }
    if(empty($email)){
        $errors['email']="Email required.";
    }

    if(count($errors)==0){
        $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $user = mysqli_fetch_assoc($result);
        $token = $user['token'];
        sendPasswordResetLink($email, $token);
        header('location: password_message.php');
        exit(0);
    }
}

//if user clicks on the reset password button
if(isset($_POST['reset-password-btn'])){
    $password = $_POST['password'];
    $passwordConf = $_POST['passwordConf'];

    if(empty($password) || empty($passwordConf)){
        $errors['password']="Password required.";
    }
    if($password !== $passwordConf){
        $errors['password']="The two passwords do not match.";
    }

    $password = password_hash($password, PASSWORD_DEFAULT);
    $email = $_SESSION['email'];

    if(count($errors)==0){
        $sql = "UPDATE users SET password='$password' WHERE email='$email'";
        $result = mysqli_query($conn, $sql);
        if($result){
            header('location: login.php');
            exit(0);
        }
    }
}

function resetPasswordToken($token){
    global $conn;
    $sql = "SELECT * FROM users WHERE token='$token' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);
    $_SESSION['email'] = $user['email'];
    header('location: reset_password.php');
    exit(0);
}
?>