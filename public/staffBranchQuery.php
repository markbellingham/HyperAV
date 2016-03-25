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
FROM 	hyperav_staff join hyperav_location ON hyperav_location.locationid = hyperav_staff.locationid
WHERE 	hyperav_location.loName = "' . $location . '"';

$results = @mysqli_query($connection, $query);
	$num_rows = mysqli_num_rows($results);
	
	if ($results) {
		if ($num_rows > 0) {
				echo '<table>
				<tr> <td> <b>First Name</b> </td> <td> <b>Last Name</b> </td> </td> </tr>';

				while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
					echo '<tr> <td>' . $row['stFName'] .  '</td><td>' . $row['stLName'] . '</td></tr>';
						
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
		
	?>
	
	<?php
	include ("../includes/layouts/footer.php");
?>