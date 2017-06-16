<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	$page_title = 'My Orders | HyperAV';
	include ("../includes/layouts/header.php");

	if (isset($_SESSION['cart'])) {
		$cart = $_SESSION['cart'];
	} else {
		$cart = array();
	}

	/* Get the model number from POST
	 If the user somehow arrives at this page without clicking on the 
	 delete button in the cart, they are redirected to the products page */
	$modelNo = get_POST_value_or_redirect("prModelNo", "products.php");


	echo '<p>You clicked on ' . $modelNo . '</p>';

	/* Remove the item from the cart and send the user back to the page displaying it.
	 This gives the impression that the delete is instant */
	unset($cart[$modelNo]);
	$_SESSION['cart'] = $cart;
	redirect_to("orders.php");

	include ("../includes/layouts/footer.php");
?>