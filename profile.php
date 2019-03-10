<?php 

session_start(); // Start the session.

$page_title = "Mi Perfil";
include 'inc/header.html'; 
include 'inc/functions.inc.php';

	if (isset($_GET['id']) && is_numeric($_GET['id'])) {
		$user_id = $_GET['id'];
	} else if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) {
		$user_id = $_SESSION['user_id'];
	} else {
		$error = "SHOW_PROFILE";
		include 'inc/bad_access.html';
		include 'inc/footer.html';
		exit;
	}

	if (isset($user_id)) {
		
		// Connect to our database:
		require '../mysqli_connect.php';

		// Create the query:
		$q = "SELECT * FROM users WHERE user_id='$user_id'";
		$r =  @mysqli_query($dbc, $q);

		if (mysqli_num_rows($r) == 1) {
			$row = mysqli_fetch_array($r, MYSQLI_NUM);

			// Get the tasks done:
			$q = "SELECT task_id, user_id, name, status, DAYOFMONTH(created_at) AS day, MONTHNAME(created_at) AS month FROM tasks WHERE $user_id = user_id ORDER BY created_at DESC";
			$r_alt = @mysqli_query($dbc, $q);

			$q = "SELECT REPLACE(created_at, RIGHT(created_at,12), '') AS shortdate, MONTHNAME(created_at) AS month, YEAR(created_at) AS year FROM tasks GROUP BY shortdate";
			$r_months = @mysqli_query($dbc, $q);

		} else {
			$error = "NO_SUCH_USER";
			include 'inc/bad_access.html';
			include 'inc/footer.html';
			exit;
		}

	}

	if (isset($_GET['date'])) {
		$date = $_GET['date'];
		$q = "SELECT task_id, user_id, name, status, DAYOFMONTH(created_at) AS day, MONTHNAME(created_at) AS month FROM tasks WHERE $user_id = user_id AND created_at LIKE '$date%' ORDER BY created_at DESC";
		$r_alt = @mysqli_query($dbc, $q);
	}

	// Check if the user is viewing is own profile, or it's a guest.
	$is_loggedin = (isset($_SESSION['user_id']) && $row[0]==$_SESSION['user_id']);

?>
<main class="main">
	<section class="section"> 
		<div class="container">
			<div class="columns">
				<div class="column is-3 is-2-fullhd">
					<div class="card">
						<div class="card-image is-hidden-touch">
							<figure class="image is-1by1">
							  <?php if ($row[8] == 'none'): ?>
							  	<img src="img/profile/none.png" alt="Placeholder image">
							  <?php else: ?>
							  	<img src="img/profile/<?= $row[8]?>" alt="Foto de <?= $row[1]?>">
							  <?php endif ?>
							</figure>
						</div>
						<div class="card-content">
							<h1 class="title is-4"><?php echo $row[1]." ".$row[2] ?></h1>
							<h2 class="subtitle is-6"><?php echo $row[3] ?></h2>
							<?php if($is_loggedin): ?><a href="edit_profile"><span class="icon"><i class="fas fa-edit"></i></span> Editar mi perfil</a><?php endif;?>
						</div>
					</div>
					<div class="box">
						<div class="content">
							<h6 class="title is-6">Filtrar por Mes:</h6>
							<ul class="subtitle is-6">
								<li><a href="profile?id=<?= $user_id?>">Todas</a></li>
								<?php while ($row_months = mysqli_fetch_array($r_months, MYSQLI_ASSOC)): ?>
									<li><a href="profile?id=<?= $user_id?>&date=<?= $row_months['shortdate'] ?>"><?= translateMonth($row_months['month'])?> <?= $row_months['year']?></a></li>
								<?php endwhile ?>
							</ul>
						</div>
					</div>
				</div>
				<div class="column">
					<h1 class="title is-3 is-hidden-tablet">Tareas</h1>
					<?php 

					$cur_date = NULL;
					while ($row_alt = mysqli_fetch_array($r_alt, MYSQLI_ASSOC)):

					 	// Start the organized tables: For the first row, there is nothing yet, so we compare it to NULL
					 	if ($cur_date == NULL): // Starts the most recent date in database ?>
					 		<div class="box">
					 		<h1 class="title is-5 is-table-title"><?= $row_alt['day']?> de <?= translateMonth($row_alt['month'])?></h1>
					 			<table class='table is-striped is-hoverable'>
					 			<thead>
					 				<tr>
					 					<?php if ($is_loggedin): ?>
					 						<th>Editar</th>
					 						<th>Eliminar</th>
					 					<?php endif ?>
					 					<th>Nombre de Tarea</th>
					 					<th>Estado</th>
					 				</tr>
					 			</thead>
					 			<tbody>
					 				<tr>
					 					<?php if ($is_loggedin): ?>
					 						<td><a href="edit_task?id=<?= $row_alt['task_id']?>"><span class="icon"><i class="fas fa-edit"></i></span></a></td>
					 						<td><a href="delete_task?id=<?= $row_alt['task_id']?>"><span class="icon"><i class="fas fa-trash-alt"></i></span></a></td>
					 					<?php endif ?>
					 					<td width="100%"><?= $row_alt['name'] ?></td>
					 					<td><?= parseStatus($row_alt['status'])?></td>
					 				</tr>
					 	<?php elseif ($cur_date != $row_alt['day'].$row_alt['month']): // Separates tables in dates ?>
					 			</tbody>
					 		</table>
					 		</div>
					 		<div class="box">
					 		<h1 class="title is-5 is-table-title"><?= $row_alt['day']?> de <?= translateMonth($row_alt['month'])?></h1>
					 		<table class='table is-striped is-hoverable'>
					 			<thead>
					 				<tr>
					 					<?php if ($is_loggedin): ?>
					 						<th>Editar</th>
					 						<th>Eliminar</th>
					 					<?php endif ?>
					 					<th>Nombre de Tarea</th>
					 					<th>Estado</th>
					 				</tr>
					 			</thead>
					 			<tbody>
					 				<tr>
					 					<?php if ($is_loggedin): ?>
					 						<td><a href="edit_task?id=<?= $row_alt['task_id']?>"><span class="icon"><i class="fas fa-edit"></i></span></a></td>
					 						<td><a href="delete_task?id=<?= $row_alt['task_id']?>"><span class="icon"><i class="fas fa-trash-alt"></i></span></a></td>
					 					<?php endif ?>
					 					<td width="100%"><?= $row_alt['name'] ?></td>
					 					<td><?= parseStatus($row_alt['status'])?></td>
					 				</tr>
					 	<?php else: // Outputs content from the current date ?>
					 		<tr>
					 			<?php if ($is_loggedin): ?>
					 				<td><a href="edit_task?id=<?= $row_alt['task_id']?>"><span class="icon"><i class="fas fa-edit"></i></span></a></td>
					 				<td><a href="delete_task?id=<?= $row_alt['task_id']?>"><span class="icon"><i class="fas fa-trash-alt"></i></span></a></td>
					 			<?php endif ?>
					 			<td width="100%"><?= $row_alt['name'] ?></td>
					 			<td><?= parseStatus($row_alt['status'])?></td>
					 		</tr>
					 	<?php endif;

					 	$cur_date = $row_alt['day'].$row_alt['month'];
					endwhile; // End of While loop ?>
				</div>
			</div>
		</div>
	</section>
</main>