<?php

session_start(); // Start the session.

if (!isset($_SESSION['agent']) OR ($_SESSION['agent'] != md5($_SERVER['HTTP_USER_AGENT']))) {
	require 'inc/login_functions.inc.php';
	redirect_user();
}

$page_title = 'Logged In!';
include 'inc/header.html'
?>
<main class="main">
	<section class="section">
		<div class="container">
			<div class="box">
				<h1 class="title">¡Hola, <?php echo $_SESSION['first_name'] ?>!</h1>
				<p class="subtitle">Has iniciado sesión</p>
				<a href="logout.php">Cerrar Sesión</a>
			</div>
		</div>
	</section>
</main>
<?php include 'inc/footer.html'; ?>