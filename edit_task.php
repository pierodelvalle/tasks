<?php 

session_start(); // Start the session.

$page_title = 'Editar una tarea';

include 'inc/header.html';

if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) {
	$id = $_GET['id'];
} elseif ((isset($_POST['id'])) && (is_numeric($_POST['id']))) {
	$id = $_POST['id'];
} else {
	$error = "EDIT";
	echo "Error";
	include 'inc/footer.html';
	exit();
}

require_once '../mysqli_connect.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$errors = [];

	if (empty($_POST['name'])) {
		$errors[] = '¡Olvidaste de poner un nombre!';
	} else {
		$na = mysqli_real_escape_string($dbc, trim($_POST['name']));
	}

	if (empty($_POST['status']) ) {
		$errors[] = '¡Olvidaste de poner un estado!';
	} else {
		$st = mysqli_real_escape_string($dbc, trim($_POST['status']));
	}

	if (empty($_POST['date'])) {
		$errors[] = "¡Te olvidaste de poner la fecha!";
	} else {
		$dt = mysqli_real_escape_string($dbc, trim($_POST['date']));
	}

	if (empty($errors)) {
		
		// Make the query
		$q = "UPDATE tasks set name='$na', status='$st', created_at = DATE('$dt') WHERE task_id = $id LIMIT 1";
		$r = @mysqli_query($dbc, $q);

		if (mysqli_affected_rows($dbc) == 1) {
			echo "<div class='container'><div class='notification is-success'>Se ha actualizado la tarea.</div></div>";
		} else {
			echo "<div class='container'><div class='notification is-danger'>La tarea no ha podido ser actualizada debido a un error del sistema :( Échale la culpa a Piero.</div></div>";
		}
	} else {
		foreach ($errors as $msg) {
			echo "<div class='container'><div class='notification is-danger'>$msg</div></div>";
		}
	}
}

$q =  "SELECT task_id, user_id, name, status, REPLACE(created_at, RIGHT(created_at, 9), '') as fecha FROM tasks WHERE task_id=$id";
$r = @mysqli_query($dbc, $q);

if (mysqli_num_rows($r) == 1) {
	$row = mysqli_fetch_array($r, MYSQLI_NUM);

	if ($row[1] != $_SESSION['user_id']){
		$error = "EDIT_UNAUTH";
		include 'inc/bad_access.html';
		include 'inc/footer.html';
		exit();
	} 
?>
<main class="main">
	<section class="section">
		<div class="container">
			<div class="columns">
				<div class="column is-6">
					<div class="box">
						<h1 class="title">Editar Tarea</h1>
						<form action="edit_task" method="POST">
							<div class="field">
								<div class="label">Nombre de la tarea: </div>
								<div class="control">
									<input type="text" class="input" name="name" value="<?= $row[2]?>" autocomplete="off">
								</div>
							</div>
							<div class="field">
								<div class="label">Estado</div>
								<div class="control">
									<div class="select">
										<select name="status">
											<option value="0" disabled="disabled">Elige el estado</option>
											<option value="1" <?php if ($row[3] == 1) { echo 'selected="selected"'; } ?> >En Progreso</option>
											<option value="2" <?php if ($row[3] == 2) { echo 'selected="selected"'; } ?> >Pendiente Aprobación</option>
											<option value="3" <?php if ($row[3] == 3) { echo 'selected="selected"'; } ?> >Finalizada</option>
										</select>
									</div>
								</div>
							</div>
							<div class="field">
								<div class="label">Fecha:</div>
								<div class="control">
									<input type="date" name="date" class="input" value="<?= $row[4]?>">
								</div>
							</div>
							<div class="field" style="display: none;">
								<div class="control">
									<input type="text" name="id" value="<?= $row[0]; ?>">
								</div>
							</div>
							<div class="field">
								<div class="control">
									<input type="submit" name="submit" value="Realizar cambios" class="button is-primary">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>
<?php 
} else {
	echo "Esta página ha sido accedida en error.";
}

mysqli_close($dbc);
include 'inc/footer.html'
?>
