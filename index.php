<?php 

session_start(); // Start the session.

$page_title = "Home";
include 'inc/header.html'; ?>
<main class="main">
	<section class="section">
		<div class="container">
			<div class="columns is-centered">
				<div class="column is-6">
					<div class="box">
						<?php if (isset($_SESSION['user_id'])): ?>
							<h1 class="title">Hola, <?= $_SESSION['first_name'] ?></h1>
						<?php else: ?>
							<h1 class="title">¡Hola!</h1>
							<h2 class="subtitle">Inicia sesión con el botón de la izquierda en el menú</h2>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>
<?php include 'inc/footer.html'; ?>