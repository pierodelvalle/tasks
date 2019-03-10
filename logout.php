<?php
// This page lets the user logout
// THis page uses sessions

session_start(); // enter the existing session

if (!isset($_SESSION['user_id'])) {
	// Need the functions
	require 'inc/login_functions.inc.php';
	redirect_user();
} else {
	$_SESSION = []; // Clear variables
	session_destroy(); // KILL the session
	setcookie("PHPSESSID", "", time()-3600, "/","",0,0); // Destroy the cookie
}

$page_title = "Logged the fuck out";
include 'inc/header.html';

?>
<main class="main">
	<section class="section">
		<div class="container">
			<h1 class="title">Logged the fuck out</h1>
		</div>
	</section>
</main>
<?php 
include 'inc/footer.html';