<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	
	$page_title = 'Reports';
	include ("../includes/layouts/header.php");

	$location = $_POST['location'];

	$query= 'SELECT AVG(orTotal) AS $total 
		FROM hyperAV_orders o 
		JOIN hyperAV_staff st ON o.staffID = st.staffID 
		JOIN hyperAV_location lo ON st.locationID = lo.locationID 
		WHERE 	lo.loName = "' . $location . '"';

	$results = @mysqli_query($connection, $query);
	$num_rows = mysqli_num_rows($results);
	
	if ($results) {
		if ($num_rows > 0) {
				echo '<table>
				<tr> <th> <b>Location</b> </th> <th> <b>Average Sales</b> </th> </tr>';

				while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
					echo '<tr> <td>' . $location .  '</td><td>'  . number_format($row['$total'],2)  . '</td><td>';
						
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