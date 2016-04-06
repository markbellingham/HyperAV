<?php
require_once ("../includes/session.php");
require_once ("../includes/db_connection.php");
require_once ("../includes/functions.php");



$page_title = 'Reports';
include ("../includes/layouts/header.php");

$total = $_POST['totalValue'];

$query= 'SELECT *
FROM 	hyperav_orders o  JOIN hyperav_customer cu ON o.customerID = cu.customerID 
WHERE 	o.ortotal BETWEEN ' . $total;

$results = @mysqli_query($connection, $query);
	$num_rows = mysqli_num_rows($results);
	
	if ($results) {
		if ($num_rows > 0) {
			echo '<table>
			<tr> <td> <b>First Name</b> </td> <td> <b>Last Name</b> </td> <td> <b>Order Date</b> </td> <td> <b>Order Total</b> </td>  </tr>';

			while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
				echo '<tr> <td>' . $row['cuFName'] .  '</td><td>' . $row['cuLName'] . '</td><td>' . $row['orDate'] . '</td><td align="right">' . $row['orTotal'] .  '</td></tr>';
					
			}
			echo '</table>';

			mysqli_free_result($results);
		} else {
			echo '<p class="error">There are no results.</p>';
		}
	} else {
		echo '<h3 class="error">System Error</h3>
		<p class="error">Report could not be retrieved.</p>';
		
		echo mysqli_error($connection);
		echo $query;
	}
mysqli_free_result($results);
mysqli_close($connection);		
?>
	
<?php
	include ("../includes/layouts/footer.php");
?>