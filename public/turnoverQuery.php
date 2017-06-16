<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	
	
	$page_title = 'Reports';
	include ("../includes/layouts/header.php");


	$query= 'SELECT SUM(orTotal) AS $total, lo.loName 
		FROM hyperAV_orders o 
		JOIN hyperAV_staff st ON o.staffID = st.staffID 
		JOIN hyperAV_location lo ON st.locationID = lo.locationID 
		GROUP BY lo.locationID';

	$results = @mysqli_query($connection, $query);
	$num_rows = mysqli_num_rows($results);
	
	if ($results) {
		if ($num_rows > 0) {
			echo '<table>
			<tr> <th> <b>Total Sales</b> </th> <th> <b>Location</b> </th> </tr>';

			while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
				echo '<tr> <td>' . $row['$total'] .  '</td><td>' . $row['loName'] . '</td></tr>';
					
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