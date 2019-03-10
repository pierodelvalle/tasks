<?php 

session_start(); // Start the session.

$page_title = 'Editar una tarea';

include 'inc/header.html';

if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
	$id = $_SESSION['user_id'];
} else {
	$error = "EDIT_PROFILE";
	include 'inc/bad_access.html';
	include 'inc/footer.html';
	exit;
}

require_once '../mysqli_connect.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$errors = [];

	// Check if a first name was written (Required!)
	if (empty($_POST['first_name'])) {
		$errors[] = '¡Olvidaste de poner un nombre!';
	} else {
		$fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
	}	

	// CHeck if a last name was written (Required!)
	if (empty($_POST['last_name'])) {
		$errors[] = '¡Olvidaste de poner un apellido!';
	} else {
		$ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
	}

	// Assign POSTed data to $bg
	$bg = $_POST['background'];

	// Check if a file has been uploaded
	if (isset($_FILES['profile'])) {
		
		$allowed = ['image/pjpeg', 'image/jpeg', 'image/JPG','image/X-PNG','image/PNG', 'image/png','image/x-png'];
		if (in_array($_FILES['profile']['type'], $allowed)) {
			
			move_uploaded_file($_FILES['profile']['tmp_name'], "img/profile/{$_FILES['profile']['name']}");
			$file_sql = ", profile_pic ='".$_FILES['profile']['name']."'"; // Incorporate query alongside new file, to make it optional

			if (file_exists ($_FILES['profile']['tmp_name']) && is_file($_FILES['profile']['tmp_name']) ) {
				unlink ($_FILES['profile']['tmp_name']);
			}

		} else {
			$errors[] = '¡Por favor, sube una imagen valida!';
		}

	} else {
		$file_sql = " "; // If the user didn't upload a file, don't do anything. Send "nothing".
	} // End of file conditional evaluation

	if (empty($errors)) {
		
		// Make the query
		$q = "UPDATE users SET first_name='$fn', last_name='$ln', background='$bg' $file_sql WHERE user_id = $id LIMIT 1";
		$r = @mysqli_query($dbc, $q);

		if (mysqli_affected_rows($dbc) == 1) {
			echo "<div class='container'><div class='notification is-success'>Se ha actualizado la información del perfil.</div></div>";
			$_SESSION['bg'] = $bg;
		} else {
			echo "<div class='container'><div class='notification is-danger'>La tarea no ha podido ser actualizada debido a un error del sistema :( Échale la culpa a Piero.</div></div>";
		}
	} else {
		foreach ($errors as $msg) {
			echo "<div class='container'><div class='notification is-danger'>$msg</div></div>";
		}
	}


}

$q =  "SELECT * FROM users WHERE user_id=$id";
$r = @mysqli_query($dbc, $q);

if (mysqli_num_rows($r) == 1) {
	$row = mysqli_fetch_array($r, MYSQLI_NUM);?>
<main class="main">
	<section class="section">
		<div class="container">
			<div class="columns">
				<div class="column is-6">
					<div class="box">
						<h1 class="title">Editar Perfil</h1>
						<form enctype="multipart/form-data" action="edit_profile" method="POST">
							<div class="field">
								<div class="label">Nombre: </div>
								<div class="control">
									<input type="text" class="input" name="first_name" value="<?= $row[1]?>" autocomplete="off">
								</div>
							</div>
							<div class="field">
								<div class="label">Apellido: </div>
								<div class="control">
									<input type="text" class="input" name="last_name" value="<?= $row[2]?>" autocomplete="off">
								</div>
							</div>
							<div class="field">
								<div class="label">Elegir fondo de pantalla:</div>
								<div class="select">
									<select name="background">
										<option <?php if($row[7] == "aqua") { echo "selected='selected'"; }; ?> value="">Ninguno</option>
										<option <?php if($row[7] == "aqua") { echo "selected='selected'"; }; ?> value="aqua">Aqua</option>
										<option <?php if($row[7] == "forest") { echo "selected='selected'"; }; ?> value="forest">Bosque</option>
										<option <?php if($row[7] == "silvered") { echo "selected='selected'"; }; ?> value="silvered">Montañas</option>
										<option <?php if($row[7] == "spooky") { echo "selected='selected'"; }; ?> value="spooky">Luna Llena</option>
										<option <?php if($row[7] == "timber") { echo "selected='selected'"; }; ?> value="timber">Madera</option>
										<option <?php if($row[7] == "winter") { echo "selected='selected'"; }; ?> value="winter">Invierno</option>
									</select>
								</div>
							</div>
							<div class="field">
								<div class="label">Foto de Perfil:</div>
								<div class="control">
									<input type="file" name="profile" class="input">
								</div>
							</div>
							<div class="field">
								<div class="control">
									<input type="hidden" name="id" value="<?= $row[0]; ?>">
								</div>
							</div>
							<div class="field">
								<div class="control">
									<input type="submit" name="submit" value="Enviar" class="button is-primary">
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
 