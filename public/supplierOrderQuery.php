<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	$page_title = 'Reports';
	include ("../includes/layouts/header.php");

	if (!isset($_SESSION['staff'])) {
	redirect_to("index.php");
	}

	$query= 'SELECT * FROM 	hyperAV_stockorderdetails sod 
		JOIN hyperAV_stock st ON sod.stockID = st.stockID 
		JOIN hyperAV_products pr ON st.prModelNo = pr.prModelNo 
		JOIN hyperAV_supplier su ON sod.supplierID = su.supplierID
		WHERE 	sod.stDeliveryDate IS NULL';

	$results = @mysqli_query($connection, $query);
	$num_rows = mysqli_num_rows($results);
	
	if ($results) {
		if ($num_rows > 0) {
			echo '<table>
			<tr> <th> <b>Supplier Name</b> </th> <th> <b>Product Name</b> </th> <th> <b>Order Quantity</b> </th> </th> </tr>';

			while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
				echo '<tr> <td>' . $row['suName'] .  '</td><td>' . $row['prName'] . '</td><td align="right">' . $row['stOrderQuantity'] . '</td></tr>';
					
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