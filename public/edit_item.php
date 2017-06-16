<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");
	require_once ("../includes/db_functions.php");

	// If the user somehow tries to load this page when not logged in as staff
	// they are redirected to the products page
	if(!isset($_SESSION['staff'])) {
		redirect_to("products.php");
	}

	// Get the product model number from the GET request
	// Using htmlspecialchars because this is data coming from the browser and it should be sanitised before being used in our SELECT query
	// If for some reason there is nothing in the GET request, the user is redirected to the products page
	$ModelNo = get_GET_value_or_redirect("prModelNo", "products.php");

	$page_title = $ModelNo . ' | HyperAV';
	include ("../includes/layouts/header.php");
?>


<h3>Edit the Product Information for <?php echo $ModelNo ?></h3>


<?php
	// To find all the relevant details for the item, two tables need to be joined together
	$query = 'SELECT * 
		FROM hyperAV_products pr 
		JOIN hyperAV_manufacturer ma ON pr.manufacturerID = ma.manufacturerID 
		WHERE pr.prModelNo = "' . $ModelNo . '"';
	$results = @mysqli_query($connection, $query);
	$num_rows = mysqli_num_rows($results);

	if ($results) {
		if ($num_rows > 0) {

			while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
				// The product details are displayed in editable text boxes
				echo '<div><img src="images/' . $row['prName'] . '.jpg" id="product_full_image" ></div>';
				echo '<div id="product_information"><table border="0"><form action="edit_item_submit.php" method="POST" >';
				echo '<tr><td><input type="text" name="prName" value="' . $row['prName'] . '"></td></tr>';
				echo '<tr><td><input type="text" name="description" value="' . $row['prDescription'] . '"></td></tr>';
				echo '<tr><td><textarea name="descr_long" rows="15" cols="80">' . $row['prDescr_Long'] . '</textarea></td></tr>';
				echo '<tr><td>&pound<input type="text" name="price" value="' . $row['prPrice'] . '" size="1"></td></tr>';
				echo '<tr><td>';
				/* The category dropdown box is populated from the categories already in the database. 
				 To create a new category the database will need to be edited from the command line. This reduces the risk of spelling errors. */
				dropdown_box("prCategory", "hyperAV_products");
				echo '</td></tr><tr><td>';

				// Like the category, the manufacturer dropdown box is populated from those already in the database.
				dropdown_box("maName", "hyperAV_manufacturer");

				echo '</td></tr>';
				echo '<tr><td>Minimum Stock: <input type="number" name="minStock" min="0" max="10000" value="' . $row['minStockLevel'] . '"></td></tr>';
				echo '<tr><td>Maximum Stock: <input type="number" name="maxStock" min="0" max="20000" value="' . $row['maxStockLevel'] . '"></td></tr>';
				echo '<tr><td><input type="hidden" name="prModelNo" value=' . $row['prModelNo'] . '>
					<input type="submit" value="Change Details"></form></td>';
				// Button to delete the item from the database altogether
				echo '<td><form action="delete_item.php" method="POST" onsubmit="return confirm("Are you sure you want to delete?");">
					<input type="hidden" name="prModelNo" value=' . $row['prModelNo'] . '>
					<input type="submit" value="Delete Item"></form></td>';
				echo '</tr></table></div>';
			}

		} else {
			echo '<p class="error">This product does not exist.</p>';
			// DEBUGGING echo '<p class="error">' . mysqli_error($connection) . '</p>
			// DEBUGGING	<p class="error">Query:'. $query . '</p>';
			// DEBUGGING var_dump($results);
		}
	} else {
		echo '<h3 class="error">System Error</h3>
		<p class="error">Product data could not be retrieved.</p>';
		//DEBUGGING echo '<p class="error">'.mysqli_error($connection).'</p>
		//DEBUGGING <p class="error">Query:'. $query . '</p>';
	}
	mysqli_close($connection);

	include ("../includes/layouts/footer.php");
?>