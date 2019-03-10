<?php // search.php
// This script runs through the database according to the parameters given to give a specific result

session_start();
$page_title = "Búsqueda";
include 'inc/header.html';
include 'inc/functions.inc.php';

$clean = (empty($_GET['user_id']) && empty($_GET['name']) && empty($_GET['date'])); // Pathetic variable to check if we're just accessing /search
require_once '../mysqli_connect.php'; // connect to DB

if (isset($_GET['user_id']) || isset($_GET['name']) || isset($_GET['date'])) {
	$id = (!empty($_GET['user_id'])) ? $_GET['user_id']: "%";
	$na = (!empty($_GET['name'])) ? "%".str_replace("+","",$_GET['name'])."%": "%";
	$dt = (!empty($_GET['date'])) ? $_GET['date']."%": "%";

	if ($_SERVER['REQUEST_METHOD'] == 'GET') {
		
		$q = "SELECT t.task_id, t.user_id, t.status, t.name, REPLACE(t.created_at, RIGHT(created_at, 9), '') as date, CONCAT(u.first_name, ' ', u.last_name) as author FROM tasks as t INNER JOIN users as u USING (user_id) WHERE t.user_id LIKE '$id' AND t.name LIKE '$na' AND t.created_at LIKE '$dt' ORDER BY created_at DESC";
		$r = @mysqli_query($dbc, $q);

	}
}
 ?>
 <main class="main">
 	<div class="section">
 		<div class="container">
 			<div class="card">
 				<div class="card-content">
 					<h1 class="title is-5">Búsqueda</h1>
 					<form action="search" method="get">
 						<div class="field">
 							<div class="control">
 								<input type="text" name="name" class="input" placeholder="Busca el nombre de una tarea..." autocomplete="off" <?php if (!$clean) { echo 'value="'.$_GET['name'].'"';} ?>>
 							</div>
 						</div>
 						<div class="field is-grouped">
 							<div class="select">
 								<select name="user_id">
 									<option value="0" selected="selected" disabled="disabled">Por usuario...</option>
 									<?php 
 									$q = "SELECT CONCAT(first_name, ' ', last_name) as name, user_id FROM users";
 									$r_alt = @mysqli_query($dbc, $q);

 									while ($row_alt = mysqli_fetch_array($r_alt, MYSQLI_ASSOC)) {?>
 										<option value="<?=$row_alt['user_id']?>" <?php if (!$clean && $row_alt['user_id'] == $na) { echo 'selected="selected"';} ?>><?=$row_alt['name']?></option>";
 									<?php } ?>
 								</select>
 							</div>
 							<div class="control">
 								<input type="date" class="input" name="date" <?php if (!$clean) { echo 'value="'.$_GET['date'].'"';} ?>>
 							</div>
 						</div>
 						<div class="field">
 							<div class="control">
 								<input type="submit" class="button is-primary">
 							</div>
 						</div>
 					</form>
 				</div>
 			</div>
 		</div>
 	</div>
 	<?php if ($_SERVER['REQUEST_METHOD'] == 'GET' && !$clean): ?>
 	<div class="section is-after" style="padding-top: 0 !important;">
 		<div class="container">
			<div class="box">
				<?php if ($r): ?>
	 			<h1 class="title is-5">Resultados</h1>
	 			<table class="table is-striped">
	 				<thead>
	 					<tr>
	 						<th>Nombre de la tarea</th>
	 						<th>Encargado</th>
	 						<th>Fecha (DD-MM-AAAA)</th>
	 						<th>Estado</th>
	 					</tr>
	 				</thead>
	 				<tbody>
		 		<?php while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)):?>
		 				<tr>
		 					<td width="50%"><?= $row['name']?></td>
		 					<td width="25%"><a href="profile?id=<?= $row['user_id']?>"><?= $row['author']?></a></td>
		 					<td width="25%"><?= date("d-m-Y", strtotime($row['date'])) ?></td>
		 					<td><?= parseStatus($row['status'])?></td>
		 				</tr>
		 		<?php endwhile; ?>
		 			</tbody>
	 			</table>
	 			<?php else: ?>
	 				<div></div>
					<div class="notification is-danger">Ha habido un error en tu búsqueda. Por favor, no utilices apóstrofes (') o comillas (").</div>
				<?php endif ?>
			</div>
 		</div>
 	</div>
 	<?php endif ?>
 </main>
 <?php include 'inc/footer.html'; ?>