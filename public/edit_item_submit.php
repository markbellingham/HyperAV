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

	$query = mysqli_prepare($connection, 'UPDATE hyperav_products SET prName = ?, prDescription = ?, prPrice = ?, prCategory = ?, minStockLevel = ? WHERE prModelNo = ?');
	if ($query === false) { trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($connection)), E_USER_ERROR); }

	$bind = mysqli_stmt_bind_param($query, "ssdsis", $name, $description, $price, $category, $minStock, $modelNo);
	if ($bind === false) { trigger_error('Bind parameters failed ' . E_USER_ERROR); }

	$exec = mysqli_stmt_execute($query);
	if ($exec === false) { 
		trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($query)), E_USER_ERROR); 
	} else {
		/* If the product information is successfully updated, the user is redirected to the information page for that product and
		 a flag is set so that the heading can say that the product was edited */
		$_SESSION['message'] = 'edited';
		redirect_to("selected_product.php?prModelNo=" . $modelNo);
	}

	mysqli_close($connection);

	include ("../includes/layouts/footer.php");
?>