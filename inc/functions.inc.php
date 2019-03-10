<?php 

// This function takes an integer from 0 to 2, and outputs the corresponding icon, assuming:
// 	0: Task is being worked on
// 	1: Task has been sent and is waiting to be approved
//  2: Task has been approved by the client and finished
function parseStatus($value) {
	switch ($value) {
		case 1:
			# code...
			echo '<span class="icon has-text-info"><i title="Trabajando" class="fas fa-spinner"></i></span>';
			break;
		case 2:
			# code...
			echo '<span class="icon has-text-warning"><i title="Pendiente de Aprobacion" class="fas fa-exclamation-triangle"></i></span>';
			break;
		case 3:
			# code...
			echo '<span class="icon has-text-success"><i title="Finalizado" class="fas fa-check-square"></i></span>';
			break;
		default:
			echo '<span class="icon has-text-danger"><i title="Error" class="fas fa-ban"></i></span>';
			break;
	}
}

function translateMonth($value) {
	switch ($value) {
		case 'January':
			echo "Enero";
			break;
		case 'February':
			echo "Febrero";
			break;
		case 'March':
			echo "Marzo";
			break;
		case 'April':
			echo "Abril";
			break;
		case 'May':
			echo "Mayo";
			break;
		case 'June':
			echo "Junio";
			break;
		case 'July':
			echo "Julio";
			break;
		case 'August':
			echo "Agosto";
			break;
		case 'September':
			echo "Setiembre";
			break;
		case 'October':
			echo "Octubre";
			break;
		case 'November':
			echo "Noviembre";
			break;
		case 'December':
			echo "December";
			break;	
		
		default:
			echo "???";
			break;
	}
}
?>
