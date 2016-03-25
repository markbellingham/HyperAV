<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	$page_title = 'Supplier Orders | HyperAV';
	include ("../includes/layouts/header.php");

	if (!isset($_SESSION['staff'])) {
		redirect_to("index.php");
	}

	if (isset($_SESSION['stockcart'])) {
		$stockcart = $_SESSION['stockcart'];
	} else {
		$stockcart = array();
	}

	/* Get the model number from POST
	 If the user somehow arrives at this page without clicking on the 
	 delete button in the cart, they are redirected to the products page */
	if (isset($_POST['prModelNo'])) {
		$modelNo = $_POST['prModelNo'];	
	} else {
		redirect_to("supplierProducts.php");
	}

	echo '<p>You clicked on ' . $modelNo . '</p>';

	/* Remove the item from the cart and send the user back to the page displaying it.
	 This gives the impression that the delete is instant */
	unset($stockcart[$modelNo]);
	$_SESSION['stockcart'] = $stockcart;
	redirect_to("supplierOrders.php");

	include ("../includes/layouts/footer.php");
?>