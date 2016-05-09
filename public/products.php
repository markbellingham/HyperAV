<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	$page_title = 'Products | HyperAV';
	include ("../includes/layouts/header.php");

	/* If the user came from the delete_item.php page, there will be a message in the SESSION and
	the page will tell the user which item was deleted.
	Then it deletes the SESSION variable so that the message does not reappear. */
	if (isset($_SESSION['message'])) {
		if ($_SESSION['message'] == "deleted") {
			echo '<h3>' . $_SESSION['modelNo'] . ' was deleted from the database</h3>';
			unset($_SESSION['message']);
		} else if ($_SESSION['message'] == "added") {
			echo '<h3>' . $_SESSION['prName'] . ' was added to your order</h3>';
			unset($_SESSION['message'], $_SESSION['prName']);
		}
	}

	if (isset($_GET['category'])) {
		$category = $_GET['category'];
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
	$query1 = "SELECT DISTINCT prCategory FROM hyperav_products ORDER BY prCategory ASC";
	$results1 = @mysqli_query($connection, $query1);
	$num_rows1 = mysqli_num_rows($results1);
	if($results1) {
		if($num_rows1 > 0) { ?>
		<form action="products.php" method="GET">
			<select name=category onchange="this.form.submit()">
				<option>Select Category</option>
				<?php while ($option = mysqli_fetch_array($results1, MYSQLI_ASSOC)) {
					if ($option['prCategory'] === $category) { ?>
						<option selected><?php echo $option['prCategory']; ?></option><?php
					} else { ?>
						<option><?php echo $option['prCategory']; ?></option><?php
					} 
				} ?>
			</select>
		</form><?php
		}
	}
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
		$query = 'SELECT * FROM hyperav_products WHERE prCategory = "' . $category . '"';
	} else {
		$query = "SELECT * FROM hyperav_products";
	}

	//echo $query;

	$results = @mysqli_query($connection, $query);
	$num_rows = mysqli_num_rows($results);
	if ($results) {
		if ($num_rows > 0) {
 
			// If there are results, they are displayed in a table
			echo'<table class="products"> 			
				<tr><td></td><td><b>Name</b></td><td><b>Description</b></td><td><b>Price</b></td><td><b>Category</b></td><td></td></tr>';

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
	mysqli_free_result($results1);
	mysqli_close($connection);
?>
</div><!-- ends main -->

<?php
	include ("../includes/layouts/footer.php");
?>