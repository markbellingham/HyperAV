<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	$page_title = 'Supplier Orders | HyperAV';
	include ("../includes/layouts/header.php");

	if (!isset($_SESSION['staff'])) {
		redirect_to("index.php");
	}

	if (isset($_GET['supplier'])) {
		$supplier = $_GET['supplier'];
	} else {
		$supplier = "";
	}
?>

<!-- Populates the drop-down box where the staff member can select from the different suppliers
	if one of the suppliers is selected, it reloads the page with the selected option as a GET request
	so that the page only shows the selected supplier  -->
<?php 
	$query1 = "SELECT DISTINCT suName FROM hyperav_supplier ORDER BY suName ASC";
	$results1 = @mysqli_query($connection, $query1);
	$num_rows1 = mysqli_num_rows($results1);
	if($results1) {
		if($num_rows1 > 0) { ?>
		<form action="prevSupplierOrders.php" method="GET">
			<select name="supplier" onchange="this.form.submit()">
				<option>Select Supplier</option>
				<?php while ($option = mysqli_fetch_array($results1, MYSQLI_ASSOC)) {
					if ($option['suName'] === $supplier) { ?>
						<option selected><?php echo $option['suName']; ?></option><?php
					} else { ?>
						<option><?php echo $option['suName']; ?></option><?php
					} 
				} ?>
			</select>
		</form><?php
		}
	}
?>

<?php
if ($supplier != "" && $supplier != "Select Supplier") {
	$query = 'SELECT suName, pr.prModelNo, prName, prPrice, stOrderDate, stDeliveryDate, stOrderQuantity
			FROM hyperav_supplier su
			JOIN hyperav_stockorderdetails sod ON su.supplierID = sod.supplierID
			JOIN hyperav_stock st ON st.stockID = sod.stockID
			JOIN hyperav_products pr ON pr.prModelNo = st.prModelNo 
			WHERE suName = "' . $supplier . '" 
			ORDER BY stOrderDate DESC, suName';
} else {
	$query = 'SELECT suName, pr.prModelNo, prName, prPrice, stOrderDate, stDeliveryDate, stOrderQuantity
			FROM hyperav_supplier su
			JOIN hyperav_stockorderdetails sod ON su.supplierID = sod.supplierID
			JOIN hyperav_stock st ON st.stockID = sod.stockID
			JOIN hyperav_products pr ON pr.prModelNo = st.prModelNo
			ORDER BY stOrderDate DESC, suName';
}

// print_r($query);

$results = @mysqli_query($connection, $query);
$num_rows = mysqli_num_rows($results);

if ($results) {
	if ($num_rows > 0) {
		$i = ""; $grandTotal = 0; // Initialise variables
		while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
			$totalPerItem = $row['prPrice'] * $row['stOrderQuantity'];
			$grandTotal += $totalPerItem;

			// The supplier orders are separated by date
			if ($i != $row['stOrderDate']) {
				echo '</table><br/><br/>';

				echo '<table border="1"><tr><td>Order Date: ' . $row['stOrderDate'] . '</td><td>Delivery Date: ' . $row['stDeliveryDate'] . '</td><td>Total: £' . number_format($grandTotal, 2) . '</td></tr></table>';

				echo '<table style="border: 1px solid black"><tr style="font-weight: bold"><td>Supplier Name</td><td>Product Name</td><td>Price</td><td>Quantity</td><td>Total Per Item</td></tr>';
				$grandTotal = 0; // reset grandTotal
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
mysqli_free_result($results1);
mysqli_close($connection);
?>



<?php
	include ("../includes/layouts/footer.php");
?>