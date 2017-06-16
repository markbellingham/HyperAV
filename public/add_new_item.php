<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");
	require_once ("../includes/db_functions.php");

	/* If the user somehow tries to load this page when not logged in as staff
	 they are redirected to the products page */
	if(!isset($_SESSION['staff'])) {
		redirect_to("products.php");
	}

	$page_title = 'Add a new product | HyperAV';
	include ("../includes/layouts/header.php");
?>


<h3>Add a new product to the database</h3>

<div id="add_new_item">
	<table>
		<form name="add_new_item" action="add_new_item_submit.php" method="POST">
			<tr><td>Model Number:</td><td><input type="text" name="modelNo"></td></tr>
			<tr><td>Product Name:</td><td><input type="text" name="name"></td></tr>
			<tr><td>Description:</td><td><textarea name="description" rows="5" cols="50"></textarea></td></tr>
			<tr><td>Price:</td><td>&pound<input type="text" name="price"></td></tr>
			
			<!-- Create the dropdown box for selecting the category -->
			<tr><td></td><td><?php
			dropdown_box("prCategory", "hyperAV_products");
			?></td></tr>
			
			<!-- Create the dropdown box for selecting the manufacturer -->
			<tr><td></td><td><?php
			dropdown_box("maName", "hyperAV_manufacturer");
			?></td></tr>
			<tr><td>Minimum Stock Level:</td><td><input type="number" name="minStock" min="0" max="10000" value="0"></td></tr>
			<tr><td></td><td><input type="submit" value="Submit"></td></tr>
		</form>
	</table>
</div> <!-- Ends "add_new_item" -->


<?php
	include ("../includes/layouts/footer.php");
?>