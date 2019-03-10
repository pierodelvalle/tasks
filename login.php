<?php 

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	require 'inc/login_functions.inc.php';
	require '../mysqli_connect.php';

	list($check, $data) = check_login($dbc, $_POST['email'], $_POST['pass']);

	if ($check) {
		session_start();
		$_SESSION['user_id'] = $data['user_id'];
		$_SESSION['first_name'] = $data['first_name'];
		$_SESSION['bg'] = $data['background'];
		$_SESSION['profile'] = $data['profile_pic'];

		//Store the HTTP_USER_AGENT

		$_SESSION['agent'] = md5($_SERVER['HTTP_USER_AGENT']);

		// Redirect
		redirect_user('loggedin.php');
	} else {
		$errors = $data;
	}

	mysqli_close($dbc);
}

include 'inc/login.inc.php';