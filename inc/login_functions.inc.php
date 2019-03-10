<?php 

function redirect_user($page = "index.php") {
	$url = 'http://' . $_SERVER["HTTP_HOST"] . dirname($_SERVER["PHP_SELF"]);
	$url = rtrim($url, '/\\');
	$url .= '/' . $page;

	header("Location: $url");
	exit();
} // End of redirect_user

function check_login($dbc, $email='',$pass='') {
	$errors = [];
	if (empty($email)) {
		$errors[]= "You forgot to enter your email.";
	} else {
		$e = mysqli_real_escape_string($dbc, trim($email));
	}
	if (empty($pass)) {
		$errors[]= "You forgot to enter your pass.";
	} else {
		$p = mysqli_real_escape_string($dbc, trim($pass));
	}

	if (empty($errors)) {
		$q = "SELECT user_id, first_name, background FROM users WHERE email='$e' AND pass=SHA1('$p')";
		$r = @mysqli_query($dbc, $q);

		if (mysqli_num_rows($r) == 1) {
			$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
			return array(true, $row);
		} else {
			$errors[] = "The email or password do not match those on file.";
		}

	} else {
		return array(false, $errors);
	} // End of error checking

} // ENd of check_login