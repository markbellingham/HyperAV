<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	/* Get the product model number from the POST request if it is set
	 If the use somehow gets to this page without clicking the delete button they are redirected. */
	if (isset($_POST['prModelNo'])) {
		$modelNo = $_POST['prModelNo'];
	} else {
		redirect_to("products.php");
	}

	$page_title = $modelNo . ' | HyperAV';
	include ("../includes/layouts/header.php");

	// echo '<p>' . $ModelNo . '</p>';

	// Create statement using prepared statement
	$query = mysqli_prepare($connection, "DELETE FROM hyperav_products WHERE prModelNo = ?");
	if ($query === false) { trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($connection)), E_USER_ERROR); }

	// Bind the parameter
	$bind = mysqli_stmt_bind_param($query, "s", $modelNo);
	if ($bind === false) { trigger_error('Binding parameters failed! ' . E_USER_ERROR); }

	// Execute the query. If successful, redirect to the Products page and send a message to display to show that the item was deleted
	$exec = mysqli_stmt_execute($query);
	if ($exec === false) {
		trigger_error('Statement execution failed! ' . htmlspecialchars(mysqli_error($query)), E_USER_ERROR);
	} else {
		echo 'Item ' . $modelNo . ' has been removed from the database';
		$_SESSION['message'] = "deleted";
		$_SESSION['modelNo'] = $modelNo;
		redirect_to("products.php");
	}

	mysqli_close($connection);

	include ("../includes/layouts/footer.php");
?>