<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	
	
	$page_title = 'Reports';
	include ("../includes/layouts/header.php");
?>

<?php

$location = $_POST['location'];

$query= 'SELECT *
FROM 	
WHERE 	lo.loName = "' . $location . '"';

$results = @mysqli_query($connection, $query);
$num_rows = mysqli_num_rows($results);

if ($results) {
	if ($num_rows > 0) {
			echo '<table>
			<tr> <td> <b>Average Sales</b> </td>  </td> </tr>';

			while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
				echo '<tr> <td>' . $row['$total'] .  '</td><td>';
					
			}
			echo '</table>';

			mysqli_free_result($results);
		} else {
			echo '<p class="error">There are no results.</p>';
		}
	} else {
		echo '<h3 class="error">System Error</h3>
		<p class="error">Report could not be retrieved.</p>';
		
	}
	mysqli_close($connection);
	
	include ("../includes/layouts/footer.php");
?>