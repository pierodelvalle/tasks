<?php 

session_start(); // Start the session.

$page_title = "Añadir Tarea";
include 'inc/header.html'; 


if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
	$u = $_SESSION['user_id'];
} else {
	$error = 'ADD';
	include 'inc/bad_access.html';
	include 'inc/footer.html';
	exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$errors = [];
	require_once '../mysqli_connect.php';

	// Check for the task name:
	if (empty($_POST['name'])) {
		$errors[] = "¡Te olvidaste de poner el nombre!";
	} else {
		$na = mysqli_real_escape_string($dbc, trim($_POST['name']));
	}

	if (empty($_POST['status'])) {
		$errors[] = "¡Te olvidaste de poner el estado!";
	} else {
		$st = mysqli_real_escape_string($dbc, trim($_POST['status']));
	}

	if (empty($_POST['date'])) {
		$errors[] = "¡Te olvidaste de poner la fecha!";
	} else {
		$dt = mysqli_real_escape_string($dbc, trim($_POST['date']));
	}

	if (empty($errors)) {
		
		$q = "INSERT INTO tasks VALUES(null, '$u', '$na', '$st', DATE('$dt'))";
		$r = @mysqli_query($dbc, $q);

		if (mysqli_affected_rows($dbc) == 1) {
			echo "<div class='container'><div class='notification is-success'>Se ha añadido la tarea.</div></div>";
		} else {
			echo "<div class='container'><div class='notification is-danger'>La tarea no ha podido ser añadida debido a un error del sistema :( Échale la culpa a Piero.</div></div>";
		}
	} else {
		foreach ($errors as $msg) {
			echo "<div class='container'><div class='notification is-danger'>$msg</div></div>";
		}
	}
}

?>
<main class="main">
	<section class="section">
		<div class="container">
			<div class="columns">
				<div class="column is-6">
					<div class="box">
						<h1 class="title">Añadir Tarea</h1>
						<form action="add_task" method="POST">
							<div class="field">
								<div class="label">Nombre de la tarea: </div>
								<div class="control">
									<input type="text" class="input" name="name" autocomplete="off" placeholder="Ej.: 'Parche de Animación para Explicativo de Compartamos'">
								</div>
							</div>
							<div class="field">
								<div class="label">Estado: </div>
								<div class="control">
									<div class="select">
										<select name="status">
											<option value="0" disabled="disabled" selected="selected">Elige el estado</option>
											<option value="1">En Progreso</option>
											<option value="2">Pendiente Aprobación</option>
											<option value="3">Finalizada</option>
										</select>
									</div>
								</div>
							</div>
							<div class="field">
								<div class="control">
									<div class="label">Fecha:</div>
									<input type="date" name="date" class="input">
								</div>
							</div>
							<div class="field">
								<div class="control">
									<input type="submit" name="submit" value="Añadir" class="button is-primary">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>
 <?php include 'inc/footer.html'; ?>