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

	// Get all the parameters from the form on the previous page using POST
	$modelNo 		= $_POST['modelNo'];
	$name 			= $_POST['name'];
	$description 	= $_POST['description'];
	$price 			= $_POST['price'];
	$category 		= $_POST['prCategory'];
	$manufacturer 	= $_POST['maName'];
	$minStock 		= $_POST['minStock'];

	$page_title = $modelNo . ' added to the database! | HyperAV';
	include ("../includes/layouts/header.php");
?>

<h3>You have submitted a new product to the database</h3>

<?php
	// echo '<p>' . $modelNo . '</p>';
	// echo '<p>' . $name . '</p>';
	// echo '<p>' . $description . '</p>';
	// echo '<p>' . $price . '</p>';
	// echo '<p>' . $category . '</p>';
	// echo '<p>' . $manufacturer . '</p>';
	// echo '<p>' . $minStock . '</p>';
	
	// First get the manufacturer ID from the manufacturer table
	$manufacturerID = get_an_ID_from_the_database("manufacturerID", "hyperAV_manufacturer", "maName", $manufacturer);

	// Create the INSERT statement using Prepared Statement to protect from SQL injection
	$query2 = mysqli_prepare($connection, 'INSERT INTO hyperAV_products (prModelNo, prName, prDescription, prPrice, prCategory, manufacturerID, minStockLevel) VALUES (?,?,?,?,?,?,?)');
	if ($query2 === false) {
		trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($connection)), E_USER_ERROR);
	}
	// Bind the values to the statement
	$bind = mysqli_stmt_bind_param($query2, "sssdsii", $modelNo, $name, $description, $price, $category, $manufacturerID, $minStock);
	if ($bind === false) {
		trigger_error('Bind param failed!', E_USER_ERROR);
	}
	// Execute the statement
	$exec = mysqli_stmt_execute($query2);
	if ($exec === false) {
		trigger_error('Statement execute failed ' . htmlspecialchars(mysqli_stmt_error($query2)), E_USER_ERROR);
	} else {
		echo '<p>' . $name . ' successfully inserted into the database</p>';
		//echo '<p>You will now be redirected to its product page.';
		$_SESSION['message'] = 'added';
		redirect_to("selected_product.php?prModelNo=" . $modelNo);
	}

	mysqli_free_result($result1);
	mysqli_close($connection);	
?>


<?php
	include ("../includes/layouts/footer.php");
?>