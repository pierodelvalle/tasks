<?php 

$page_title = "Login";
include 'inc/header.html';

if (isset($errors) && !empty($errors)) {
	echo '<h1>Error!</h1> <p class="error">The following error(s) occurred:<br />';

	foreach ($errors as $msg) {
		echo " - $msg<br />\n";
	}

	echo '</p><p>Please try again.</p>';
}

 ?>
<main>
	<section class="section">
		<div class="container">
			<div class="columns">
				<div class="column is-4">
					<div class="box">
						<h1 class="title">Iniciar Sesión</h1>
						<form action="login.php" method="post">
							<div class="field">
								<div class="label">Email</div>
								<div class="control">
									<input type="text" class="input" name="email">
								</div>
							</div>
							<div class="field">
								<div class="label">Contraseña</div>
								<div class="control">
									<input type="password" class="input" name="pass">
								</div>
							</div>
							<div class="field">
								<div class="control">
									<input type="submit" class="button is-primary" name="submit">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

<?php include 'inc/footer.html' ?>