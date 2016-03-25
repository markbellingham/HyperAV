<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	/* If the user somehow tries to load this page when not logged in as staff
	 they are redirected to the products page */
	if(!isset($_SESSION['staff'])) {
		redirect_to("products.php");
	}

	$modelNo 		= $_POST['prModelNo'];
	$name 			= $_POST['prName'];
	$description 	= $_POST['description'];
	$price 			= $_POST['price'];
	$category 		= $_POST['category'];
	$maName 		= $_POST['maName'];
	$minStock 		= $_POST['minStock'];
	$maxStock 		= $_POST['maxStock'];

	$page_title = $modelNo . ' edited! | HyperAV';
	include ("../includes/layouts/header.php");

	// echo '<p>' . $ModelNo . '</p>';
	// echo '<p>' . $name . '</p>';
	// echo '<p>' . $description . '</p>';
	// echo '<p>' . $price . '</p>';
	// echo '<p>' . $category . '</p>';
	// echo '<p>' . $maName . '</p>';
	// echo '<p>' . $minStock . '</p>';
	// echo '<p>' . $maxStock . '</p>';

	$query = 'UPDATE hyperav_products SET prName ="' . $name . '", prDescription = "' . $description . '", prPrice = ' . $price . ', prCategory = "' . $category . '", minStockLevel = ' . $minStock . ' WHERE prModelNo = "' . $modelNo . '"';
	$results = @mysqli_query($connection, $query);
	$num_rows = mysqli_affected_rows($connection);

	// echo '<p>' . $query . '</p>';

	if ($results) {
		if($num_rows > 0) {
			/* If the product information is successfully updated, the user is redirected to the information page for that product and
			 a flag is set so that the heading can say that the product was edited */
			$_SESSION['message'] = 'edited';
			redirect_to("selected_product.php?prModelNo=" . $modelNo);
		} else {
			echo '<p>Product information for ' . $name . ' could not be updated</p>';
			// echo '<p>' . mysqli_error($connection) . '</p>';
		}
	} else {
		echo '<p>There was an error with the database</p>';
		// echo '<p>' . mysqli_error($connection) . '</p>';
	}
?>


<?php
	include ("../includes/layouts/footer.php");
?>