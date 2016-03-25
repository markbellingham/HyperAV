<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	$page_title = 'Update Order | HyperAV';
	include ("../includes/layouts/header.php");

	// Check the SESSION for a cart and create one if it is not there
	if (isset($_SESSION['cart'])) {
		$cart = $_SESSION['cart'];
	} else {
		$cart = array();
	}

	/* If the POST contains parameters for the model number and quantity (and it should do!) the item quantity is updated and 
	the user is redirected back to the orders page. If there are somehow no parameters, the user is redirected back to the orders page anyway */
	if (isset($_POST['prModelNo']) && isset($_POST['quantity'])) {
		$modelNo = $_POST['prModelNo'];
		$quantity = $_POST['quantity'];
		echo '<p>You clicked on ' . $modelNo . '</p>';
		echo '<p>Quantity is ' . $quantity . '</p>';

		$cart[$modelNo] = $quantity;
		$_SESSION['cart'] = $cart;
		redirect_to("orders.php");
	} else {
		redirect_to("orders.php");
	}

	// print_r($cart);

	include ("../includes/layouts/footer.php");
?>