<?php 

session_start(); // Start the session.

$page_title = 'Borrar tarea';
include 'inc/header.html';

if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) {
	$id = $_GET['id'];
} elseif ((isset($_POST['id'])) && (is_numeric($_POST['id']))) {
	$id = $_POST['id'];
} else {
	$error = "Esta página ha sido accedida por error.";
	include 'inc/bad_access.html';
	include 'inc/footer.html';
	exit();
}

require_once('../mysqli_connect.php');

// CHeck if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	# code...
	if ($_POST['sure'] == 'Yes') {
		// Delete the record

		// Make the query:
		$q = "DELETE FROM tasks WHERE task_id=$id LIMIT 1";
		$r = @mysqli_query ($dbc, $q);
		if (mysqli_affected_rows($dbc) == 1) {
			// If it ran OK

			// Print a message
			echo "La tarea ha sido eliminada correctamente.";
		} else {
			// IF the query was NOT OK.
			echo "La tarea no ha podido ser borrada debido a un error del sistema.";
		}
	} else {
		echo "La tarea NO ha sido eliminada";
	}
} else {
	// Show the form

	$q = "SELECT name FROM tasks WHERE task_id = $id";
	$r = @mysqli_query($dbc, $q);

	if (mysqli_num_rows($r) == 1) {
		// Valid User ID, show the form.

		// GET user information:
		$row = mysqli_fetch_array($r, MYSQLI_NUM);

		// Display the record being deleted:
		?>

	<main class="main">
		<div class="section">
			<div class="container">
				<div class="box">
					<h3 class="title is-5">Eliminar tarea: <?php echo $row[0]; ?></h3>
					<p class="subtitle is-5 is-danger">¿Quieres eliminar esta tarea? La acción no se puede deshacer.</p>
					<form action="delete_task" method="post">
						<div class="field">
							<div class="radio">
								<input type="radio" name="sure" value="Yes"> Si
								<input type="radio" name="sure" value="No"> No
							</div>
						</div>
						<div class="field">
							<div class="control">
								<input type="submit" name=submit value="Submit" class="button is-danger">
							</div>
						</div>
						<input type="hidden" name="id" value="<?php echo $id; ?>">
					</form>
				</div>
			</div>
		</div>
	</main>
<?php 
	} else {
		// Not a valid user ID
		echo "Esta página ha sido accedida por error.";
	}
} // End of the main submission conditional.

mysqli_close($dbc);
include 'inc/footer.html';