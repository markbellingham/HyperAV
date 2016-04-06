<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	$page_title = 'Supplier Orders | HyperAV';
	include ("../includes/layouts/header.php");

	if (!isset($_SESSION['staff'])) {
		redirect_to("index.php");
	}


$query = 'SELECT suName, pr.prModelNo, prName, prPrice, stOrderDate, stDeliveryDate, stOrderQuantity
		FROM hyperav_supplier su
		JOIN hyperav_stockorderdetails sod ON su.supplierID = sod.supplierID
		JOIN hyperav_stock st ON st.stockID = sod.stockID
		JOIN hyperav_products pr ON pr.prModelNo = st.prModelNo
		ORDER BY stOrderDate DESC, suName';

$results = @mysqli_query($connection, $query);
$num_rows = mysqli_num_rows($results);

if ($results) {
	if ($num_rows > 0) {
		$i = "";
		while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
			$totalPerItem = $row['prPrice'] * $row['stOrderQuantity'];
			$grandTotal += $totalPerItem;

			// The supplier orders are separated by date
			if ($i != $row['stOrderDate']) {
				echo '</table><br/><br/>';

				echo '<table border="1"><tr><td>Order Date: ' . $row['stOrderDate'] . '</td><td>Delivery Date: ' . $row['stDeliveryDate'] . '</td><td>Total: ' . number_format($grandTotal, 2) . '</td></tr></table>';

				echo '<table style="border: 1px solid black"><tr style="font-weight: bold"><td>Supplier Name</td><td>Product Name</td><td>Price</td><td>Quantity</td><td>Total Per Item</td></tr>';
			}

			echo '<tr><td>' . $row['suName'] . '</td>
				<td>' . $row['prName'] . '</td>
				<td>£' . $row['prPrice'] . '</td>
				<td>' . $row['stOrderQuantity'] . '</td>
				<td>£' . number_format($totalPerItem, 2) . '</td></tr>';

			$i = $row['stOrderDate'];
		}
	} else {
		// echo 'There are no stock orders from ' . $supplier;
	}
}

mysqli_free_result($results);
mysqli_close($connection);
?>



<?php
	include ("../includes/layouts/footer.php");
?>