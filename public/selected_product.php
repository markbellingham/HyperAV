<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	/* Get the product model number from the GET request
	 Using htmlspecialchars because this is data coming from the browser and it should be sanitised before being used in our SELECT query 
	 If the user somehow gets here without selecting a product, they are redirected to the products page*/
	if (isset($_GET['prModelNo'])) {
		$ModelNo = htmlspecialchars($_GET['prModelNo']);
	} else {
		redirect_to("products.php");
	}

	$page_title = $ModelNo . ' | HyperAV';
	include ("../includes/layouts/header.php");
?>

<!-- <h3>Product Information for <?php echo $ModelNo ?></h3> -->
<div id="main">
<?php
	$query = 'SELECT * FROM hyperav_products WHERE prModelNo = "' . $ModelNo . '"';
	$results = @mysqli_query($connection, $query);
	$num_rows = mysqli_num_rows($results);

	if ($results) {
		if ($num_rows > 0) {

			while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
				/* Checks if there is a SESSION message so that the page can display an appropriate heading */
				if (isset($_SESSION['message'])) {
					if($_SESSION['message'] == "added") {
						echo '<h3>Product ' . $row['prName'] . ' has been added to the database.</h3>';
						unset($_SESSION['message']);
					} else if ($_SESSION['message'] == "edited") {
						echo '<h3>Product ' . $row['prName'] . ' has been edited</h3>';
						unset($_SESSION['message']);
					}
				} else {
					echo '<h3>Product information for ' . $row['prName'] . '</h3>';
					echo '<p>' . $row['prDescription'] . '</p>';
				}
				echo '<div><img src="images/' . $row['prName'] . '.jpg" id="product_full_image" ></div>';
				echo '<div id="product_information_div" class="tran2"><table id="product_information_table" border="1">
					<tr><td colspan="3">
					<a href="#" class="hide">[hide]</a>
					<a href="#" class="show">[show]</a>
					<div id="description_long" class="tran15">
					' . $row['prDescr_Long'] . '</div></td></tr> <!--ends description_long-->
					<tr><td>&pound' . $row['prPrice'] . '</td>
					<td><form action="add_to_order.php" method="POST">
					<input type="hidden" name="prModelNo" value=' . $row['prModelNo'] . '>
					<input type="submit" value="Buy"></form></td>';
				// The edit product button is only visible if a staff member is logged in
				if (isset($_SESSION['staff'])) {
					echo '<td><form action="edit_item.php" method="GET">
						<input type="hidden" name="prModelNo" value=' . $row['prModelNo'] . '>
						<input type="submit" value="Edit"></form></td></tr>';
				}
				echo '</table></div>'; // ends product_information
				$category = $row['prCategory'];
			}

		} else {
			echo '<p class="error">This product does not exist.</p>';
		}
	} else {
		echo '<h3 class="error">System Error</h3>
		<p class="error">Product data could not be retrieved.</p>';
		//DEBUGGING echo '<p class="error">'.mysqli_error($conn).'</p>
		//DEBUGGING <p class="error">Query:'. $query . '</p>';
	}
	echo '</div>';

	/* The page displays a row of related items across the bottom of the page.
	Related items are those from the same category. It is limited to 9 results to minimise horizontal scrolling */
	$query2 = 'SELECT * FROM hyperav_products WHERE prCategory = "' . $category . '" LIMIT 9';
	$results2 = @mysqli_query($connection, $query2);
	$num_rows2 = mysqli_num_rows($results2);

	if ($results2) {
		if ($num_rows2 > 0) {

			echo '<table id="suggested_items">';
			echo '<tr><td colspan="3">More items related to your query</td></tr><tr>';
			while ($row2 = mysqli_fetch_array($results2, MYSQLI_ASSOC)) {
				echo '<td><img src="images/' . $row2['prName'] . '.jpg" id="product_images"></td>';
			}
			echo '</tr><tr>';

			/* Reset the pointer in the results so we can loop through them again
			This is so that the picture and the product name can be displayed on separate rows in the table*/
			mysqli_data_seek($results2, 0);
			while ($row2 = mysqli_fetch_array($results2, MYSQLI_ASSOC)) {
				echo '<td><p><a href="selected_product.php?prModelNo=' . $row2['prModelNo'] . '">' . $row2['prName'] . '</a></p><p>&pound' . $row2['prPrice'] . '</td>';
			}

			echo '</tr></table>';
		} else {
			echo '<p class="error">No related products exist.</p>';
			// DEBUGGING echo $query2;
		}
	} else {
		echo '<h3 class="error">System Error</h3>
		<p class="error">Product data could not be retrieved.</p>';
		//DEBUGGING echo '<p class="error">'.mysqli_error($conn).'</p>';
		//DEBUGGING echo '<p class="error">Query:'. $query . '</p>';
	}

	// Release the results and the connection
	mysqli_free_result($results);
	mysqli_free_result($results2);
	mysqli_close($connection);
?>


<?php
	include ("../includes/layouts/footer.php");
?>