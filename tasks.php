<?php 

session_start(); // Start the session.
$page_title = "Todas las tareas";
include 'inc/header.html';
include 'inc/functions.inc.php';

// To future Piero: I'm so sorry.

?>

<main class="main">
	<div class="section">
		<div class="container">
			<h1 class="title">Tareas</h1>
			<div class="columns">
			<?php // Connect to database and make query

				require '../mysqli_connect.php';

				//$q = "SELECT t.task_id, t.user_id, t.name, t.status, DAYOFMONTH(t.created_at) AS day, MONTHNAME(t.created_at) AS month, u.first_name,u.last_name FROM tasks AS t INNER JOIN users AS u USING(user_id) WHERE t.user_id = 1 ORDER BY created_at DESC";
				$q = "SELECT t.task_id, t.user_id, t.status, t.name, DAYOFMONTH(t.created_at) AS day, MONTHNAME(t.created_at) AS month, u.first_name,u.last_name FROM tasks AS t INNER JOIN users AS u USING(user_id) ORDER BY last_name, created_at DESC";
				$r_alt = @mysqli_query($dbc, $q);?>
					
				<?php // Start loop for the tasks of an User

				$cur_date = NULL; $cur_user = NULL; // Start variables to keep track of the current user and date

				while ($row_alt = mysqli_fetch_array($r_alt, MYSQLI_ASSOC)): // Loop through the database results

					if ($cur_user == NULL) { // Opening tag of the first column. Only need to be output once.
						echo "<div class='column'>";
					}

					if (!($cur_user == NULL) && $cur_user != $row_alt['user_id']) { // Check if we are looping through a different user's rows
						$cur_user = $row_alt['user_id']; // Change the current user for new loop
						$cur_date = NULL; // Resets current date for the new loop ?>
								</div> <!-- Close last table --> 
							</div> <!-- Close box -->
						</div><!-- Close Column-->
						<div class='column <?= $row_alt['first_name'] ?>-col'>
					 		<div class="box"><h1 class="title is-4"><a href="profile?id=<?= $row_alt['user_id']?>"><?= $row_alt['first_name']." ".$row_alt['last_name'] ?></a></h1></div>
					 		<div class="box"><h1 class="title is-5 is-table-title"><?= $row_alt['day']?> de <?= translateMonth($row_alt['month'])?></h1>
					 		<div class="table-fake">
				 				<div class="table-fake-row">
				 					<div><?= $row_alt['name'] ?></div>
				 					<div><?= parseStatus($row_alt['status'])?></div>
				 				</div>

					<?php $cur_date = $row_alt['day'].$row_alt['month'];
					} else {
					 	// Start the organized tables: For the first row, there is nothing yet, so we compare it to NULL
					 	if ($cur_date == NULL && $cur_user == NULL): // Starts the most recent date in database ?>
					 		<div class="box"><h1 class="title is-4"><a href="profile?id=<?= $row_alt['user_id']?>"><?= $row_alt['first_name']." ".$row_alt['last_name'] ?></a></h1></div>
					 		<div class="box"><h1 class="title is-5 is-table-title"><?= $row_alt['day']?> de <?= translateMonth($row_alt['month'])?></h1>
					 		<div class="table-fake">
				 				<div class="table-fake-row">
				 					<div><?= $row_alt['name'] ?></div>
				 					<div><?= parseStatus($row_alt['status'])?></div>
				 				</div>
					 	<?php elseif ($cur_date != $row_alt['day'].$row_alt['month'] && $cur_user != NULL): // Separates tables in dates ?>
					 		</div></div><!-- Close table -->
					 		<div class="box"><h1 class="title is-5 is-table-title"><?= $row_alt['day']?> de <?= translateMonth($row_alt['month'])?></h1>
					 		<div class="table-fake">
				 				<div class="table-fake-row">
				 					<div><?= $row_alt['name'] ?></div>
				 					<div><?= parseStatus($row_alt['status'])?></div>
				 				</div>
					 	<?php else: // Outputs content from the current date ?>
						 		<div class="table-fake-row">
						 			<div><?= $row_alt['name'] ?></div>
						 			<div><?= parseStatus($row_alt['status'])?></div>
						 		</div>
					 	<?php endif; // End of tasks loop

					 	$cur_date = $row_alt['day'].$row_alt['month'];
						$cur_user = $row_alt['user_id'];

					} // End of if/else conditional
				endwhile; // End of While loop ?>
		</div><!-- End of .columns container -->
	</div>
</main>
<?php include 'inc/footer.html' ?>