<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	$page_title = 'Update Order | HyperAV';
	include ("../includes/layouts/header.php");

	if (!isset($_SESSION['staff'])) {
		redirect_to("index.php");
	}

	// Check the SESSION for a cart and create one if it is not there
	if (isset($_SESSION['stockcart'])) {
		$stockcart = $_SESSION['stockcart'];
	} else {
		$stockcart = array();
	}

	/* If the POST contains parameters for the model number and quantity (and it should do!) the item quantity is updated and the user is 
	redirected back to the supplierOrders page. If there are somehow no parameters, the user is redirected back to the supplierOrders page anyway */
	if (isset($_POST['prModelNo']) && isset($_POST['quantity'])) {
		$modelNo = $_POST['prModelNo'];
		$quantity = $_POST['quantity'];

		$stockcart[$modelNo] = $quantity;
		$_SESSION['stockcart'] = $stockcart;
		//echo '<p>You clicked on ' . $modelNo . '</p>';
		//echo '<p>Quantity is ' . $quantity . '</p>';
		//print_r($stockcart);
		redirect_to("supplierOrders.php");
	} else {
		//echo '<p>You clicked on ' . $modelNo . '</p>';
		//echo '<p>Quantity is ' . $quantity . '</p>';
		//print_r($stockcart);
		redirect_to("supplierOrders.php");
	}

	include ("../includes/layouts/footer.php");
?>