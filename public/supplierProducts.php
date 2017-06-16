<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");
	require_once ("../includes/db_functions.php");

	$page_title = 'Supplier Products | HyperAV';
	include ("../includes/layouts/header.php");

	if (!isset($_SESSION['staff'])) {
		redirect_to("index.php");
	}

	if (isset($_GET['suName'])) {
		$suName = $_GET['suName'];
	} else {
		$suName = "";
	}
?>


<h3>Supplier Products</h3>


<!-- Populates the drop-down box where the staff member can select from the different suppliers.
	If one of the suppliers is selected, it reloads the page with the selected option as a GET request
	so that the page only shows the selected supplier's products  -->
<?php 
	dropdown_js_reload("suName", "hyperAV_supplier");

	// Checks if we have come from the supplier drop down selector
	if(isset($_GET['suName']) && ($_GET['suName'] != "Select Supplier")) {
		$query = 'SELECT DISTINCT pr.prModelNo, pr.prName, pr.prDescription, pr.prPrice, pr.prCategory, su.suName 
		FROM hyperAV_products pr 
		JOIN hyperAV_stock st  ON pr.prModelNo = st.prModelNo 
		JOIN hyperAV_stockOrderDetails stor ON st.stockID =stor.stockID 
		JOIN hyperAV_supplier su ON su.supplierID=stor.supplierID 
		WHERE su.suName = "' . $_GET['suName'] . '" 
		ORDER BY pr.prName';
		
	} else {
		$query = 'SELECT DISTINCT pr.prModelNo, pr.prName, pr.prDescription, pr.prPrice, pr.prCategory, su.suName 
		FROM hyperAV_products pr 
		JOIN hyperAV_stock st  ON pr.prModelNo = st.prModelNo 
		JOIN hyperAV_stockOrderDetails stor ON st.stockID =stor.stockID 
		JOIN hyperAV_supplier su ON su.supplierID=stor.supplierID 
		ORDER BY pr.prName';	
	}

	// echo $query;

	$results = @mysqli_query($connection, $query);
	$num_rows = mysqli_num_rows($results);
	if ($results) {
		if ($num_rows > 0) {

			// If there are results, they are displayed in a table
			echo '<table class="products sortable">
			<tr><th class="sorttable_nosort"></th><th>Name</th><th class="sorttable_nosort">Description</th><th>Price</th><th>Category</th><th class="sorttable_nosort">Supplier Name</th><th></th></tr>';

			while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
				echo '<tr><td><img src="images/' . $row['prName'] . '.jpg" id="product_images"></td>
					<td><a href="selected_product.php?prModelNo=' . $row['prModelNo'] . '">' . $row['prName'] . '</a></td>
					<td>' . $row['prDescription'] . '</td><td>&pound' . $row['prPrice'] . '</td>
					<td>' . $row['prCategory'] . '</td>
					<td>' . $row['suName'] . '</td>
					<td><form action="supplierAddToOrder.php" method="POST">
					<input type="hidden" name="prModelNo" value=' . $row['prModelNo'] . '>
					<input type="submit" value="Order"></form></td></tr>';
			}
			echo '</table>';

		} else {
			// If the system was able to query the database but returned no results, we end up here
			echo '<p class="error">There are no products.</p>';
		}
	} else {
		// If there wasa a problem with the database query itself, we end up here.
		echo '<h3 class="error">System Error</h3>
		<p class="error">Product data could not be retrieved.</p>';
		//DEBUGGING echo '<p class="error">'.mysqli_error($connection).'</p>';
		//DEBUGGING echo '<p class="error">Query:'. $query . '</p>';
	}

	// Clean up variables and close the connection
	mysqli_free_result($results);
	mysqli_close($connection);

	include ("../includes/layouts/footer.php");
?>