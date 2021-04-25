<?php
require('lib/common.php');

$error = '';
$success = '';

$twig = twigloader();

if(isset($_POST["loginsubmit"])){
	//check if user has inputed a username.
	if(empty(trim($_POST['username']))){
        $error .= 'Please enter your username! ';
    } else{
        $username = trim(htmlspecialchars($_POST['username']));
    }
	
	//check if user has inputed a password.
	if(empty(trim($_POST['password']))){
        $error .= 'Please enter your password! ';
    } else{
        $password = trim(htmlspecialchars($_POST['password']));
    }
	
	if(empty($error)) {
		$userPass = fetch("SELECT password FROM users WHERE username = ?", [$username]);
		if (password_verify($password, $userPass['password'])) {
			$success = true;
		} else {
			$success = false;
		}
	}
}

echo $twig->render('login.twig', [
'error' => $error,
'success' => $success
]);