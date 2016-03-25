<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	
	
	$page_title = 'Reports';
	include ("../includes/layouts/header.php");
?>

<?php



$query= 'SELECT SUM(orTotal) AS $total, lo.loName 
FROM 	hyperav_orders o JOIN hyperav_staff st ON o.staffID = st.staffID JOIN hyperav_location lo ON st.locationID = lo.locationID 
GROUP BY lo.locationID';

$results = @mysqli_query($connection, $query);
	$num_rows = mysqli_num_rows($results);
	
	if ($results) {
		if ($num_rows > 0) {
				echo '<table>
				<tr> <td> <b>Total Sales</b> </td> <td> <b>Location</b> </td> </td> </tr>';

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
		
	?>
	
	<?php
	include ("../includes/layouts/footer.php");
?>