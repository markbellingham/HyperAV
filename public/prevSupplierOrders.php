<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");
	require_once ("../includes/db_functions.php");

	$page_title = 'Supplier Orders | HyperAV';
	include ("../includes/layouts/header.php");

	if (!isset($_SESSION['staff'])) {
		redirect_to("index.php");
	}

	if (isset($_GET['suName'])) {
		$url_parameter = $_GET['suName'];
	} else {
		$url_parameter = "";
	}


	/*Populates the drop-down box where the staff member can select from the different suppliers
	if one of the suppliers is selected, it reloads the page with the selected option as a GET request
	so that the page only shows the selected supplier*/

	dropdown_js_reload("suName", "hyperAV_supplier");

	if ($url_parameter != "" && $url_parameter != "Select Supplier") {
		$query = 'SELECT suName, pr.prModelNo, prName, prPrice, stOrderDate, stDeliveryDate, stOrderQuantity
				FROM hyperAV_supplier su
				JOIN hyperAV_stockOrderDetails sod ON su.supplierID = sod.supplierID
				JOIN hyperAV_stock st ON st.stockID = sod.stockID
				JOIN hyperAV_products pr ON pr.prModelNo = st.prModelNo 
				WHERE suName = "' . $url_parameter . '" 
				ORDER BY stOrderDate DESC, suName';
	} else {
		$query = 'SELECT suName, pr.prModelNo, prName, prPrice, stOrderDate, stDeliveryDate, stOrderQuantity
				FROM hyperAV_supplier su
				JOIN hyperAV_stockOrderDetails sod ON su.supplierID = sod.supplierID
				JOIN hyperAV_stock st ON st.stockID = sod.stockID
				JOIN hyperAV_products pr ON pr.prModelNo = st.prModelNo
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

				// The supplier orders are separated by date
				if ($i != $row['stOrderDate']) {
					echo '</table><br/><br/>';

					echo '<table border="1"><tr><th>Order Date: ' . $row['stOrderDate'] . '</th><th>Delivery Date: ' . $row['stDeliveryDate'] . '</th><th style="text-align: right">Total: £' . number_format($grandTotal, 2) . '</th></tr></table>';

					echo '<table style="border: 1px solid black"><tr style="font-weight: bold">
						<th>Supplier Name</th>
						<th>Product Name</th>
						<th style="text-align: center">Price</th>
						<th style="text-align: center">Quantity</th>
						<th style="text-align: center">Total Per Item</th></tr>';
					$grandTotal = 0; // reset grandTotal
				}

				echo '<tr><td>' . $row['suName'] . '</td>
					<td>' . $row['prName'] . '</td>
					<td style="text-align: right">£' . $row['prPrice'] . '</td>
					<td style="text-align: center">' . $row['stOrderQuantity'] . '</td>
					<td style="text-align: right">£' . number_format($totalPerItem, 2) . '</td></tr>';

				$i = $row['stOrderDate'];
				$grandTotal += $totalPerItem;
			}
		} else {
			// echo 'There are no stock orders from ' . $supplier;
		}
	}

	mysqli_free_result($results);
	// mysqli_free_result($results1);
	mysqli_close($connection);

	include ("../includes/layouts/footer.php");
?>