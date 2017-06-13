<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");
	require_once ("../includes/db_functions.php");

	$page_title = 'Products | HyperAV';
	include ("../includes/layouts/header.php");

	/*The add_to_order and delete_item pages set a message about what happened.
	If present, the message is displayed here and the SESSION variables are unset */
	if (isset($_SESSION['message'])) {
		if ($_SESSION['message'] == "deleted") {
			echo '<h3>' . $_SESSION['modelNo'] . ' was deleted from the database</h3>';
			unset($_SESSION['message']);
		} else if ($_SESSION['message'] == "added") {
			echo '<h3>' . $_SESSION['prName'] . ' was added to your order</h3>';
			unset($_SESSION['message'], $_SESSION['prName']);
		}
	}

	if (isset($_GET['prCategory'])) {
		$category = $_GET['prCategory'];
	} else {
		$category = "";
	}
?>

<div id="main">
<h3>Products</h3>

<!-- Populates the drop-down box where the customer can select from the different categories
	if one of the categories is selected, it reloads the page with the selected option as a GET request
	so that the page only shows the selected category  -->
<?php 
	dropdown_js_reload("prCategory", "hyperAV_products");
?>

<!-- <noscript><INPUT type="submit" value="Select" name=category></noscript> -->

<?php 
	// if a staff member is logged in, a link is provided to add a new product to the database
	if (isset($_SESSION['staff'])) {
		echo '<p><a href="add_new_item.php" id="add_new">Add a new product to the database</a></p>';
	}

	/* Checks if we have come from the category drop down selector
	in which case the page only shows products from that category, otherwise it shows them all */
	if ($category != "" && $category != "Select Category") {
		$query = 'SELECT * FROM hyperAV_products WHERE prCategory = "' . $category . '"';
	} else {
		$query = "SELECT * FROM hyperAV_products";
	}

	//echo $query;

	$results = @mysqli_query($connection, $query);
	$num_rows = mysqli_num_rows($results);
	if ($results) {
		if ($num_rows > 0) {
 
			// If there are results, they are displayed in a table
			echo'<table class="products sortable"> 			
				<tr><th class="sorttable_nosort"></th><th>Name</th><th class="sorttable_nosort">Description</th><th>Price</th><th class="sorttable_nosort">Category</th><th class="sorttable_nosort"></th></tr>';

			 while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
				echo '<p> <tr><td><img src="images/' . $row['prName'] . '.jpg" id="product_images"></td><td><a href="selected_product.php?prModelNo=' . $row['prModelNo'] . '">' . $row['prName'] . '</a></td><td>' . $row['prDescription'] . '</td><td>&pound' . $row['prPrice'] . '</td><td>' . $row['prCategory'] . '</td><td>
					<form action="add_to_order.php" method="POST">
					<input type="hidden" name="prModelNo" value=' . $row['prModelNo'] . '>
					<input type="submit" value="Buy"></form></td></tr> </p>'; 
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
	// mysqli_free_result($results1);
	mysqli_close($connection);
?>
</div><!-- ends main -->

<?php
	include ("../includes/layouts/footer.php");
?>
