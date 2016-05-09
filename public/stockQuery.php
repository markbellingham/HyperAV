<?php
require_once ("../includes/session.php");
require_once ("../includes/db_connection.php");
require_once ("../includes/functions.php");



$page_title = 'Reports | HyperAV';
include ("../includes/layouts/header.php");


$location = $_POST['location'];

$query = 'SELECT pr.prModelNo, prName, prDescription, prCategory, stQuantity, maName, suName
		FROM hyperav_stock st
		JOIN hyperav_products pr 			ON pr.prModelNo = st.prModelNo 
		JOIN hyperav_manufacturer ma 		ON ma.manufacturerID = pr.manufacturerID 
		JOIN hyperav_location lo 			ON lo.locationID = st.locationID 
		JOIN hyperav_stockorderdetails sod 	ON sod.stockID = st.stockID 
		JOIN hyperav_supplier su 			ON su.supplierID = sod.supplierID 
		WHERE lo.loName = "' . $location . '"
		ORDER BY prCategory';

$results = @mysqli_query($connection, $query);
$num_rows = mysqli_num_rows($results);

if ($results) {
	if ($num_rows > 0) {
		echo '<table>
		<tr><td colspan="6"><b>Stock report for ' . $location . '</b></td></td></tr>
		<tr style="font-weight: bold"><th>Product Name</th><th>Description</th><th>Category</th><th>Stock Quantity</th><th>Manufacturer</th><th>Supplier</th></tr>';

		while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
			echo '<tr><td>' . $row['prName'] . '</td>
				<td>' . $row['prDescription'] . '</td>
				<td>' . $row['prCategory'] . '</td>
				<td>' . $row['stQuantity'] . '</td>
				<td>' . $row['maName'] . '</td>
				<td>' . $row['suName'] . '</td>
				<td><form action="supplierAddToOrder.php" method="POST">
				<input type="hidden" name="prModelNo" value="' . $row['prModelNo'] . '">
				<input type="submit" value="Order"></form></td></tr>';				
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