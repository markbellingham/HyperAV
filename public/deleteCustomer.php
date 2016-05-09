<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	if (!isset($_SESSION['staff'])) {
		redirect_to("index.php");
	}

	// Get the customer number from the POST request
	$id = $_POST['customerID'];
	$cuFName = $_POST['cuFName'];
	$cuLName = $_POST['cuLName'];

	$page_title = $id . ' | HyperAV';
	include ("../includes/layouts/header.php");

	// Create the DELETE query using mysqli prepared statements
	$query = mysqli_prepare($connection, 'DELETE FROM hyperav_customer WHERE customerID = ?');
	if ($query === false) { trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($connection)), E_USER_ERROR); }

	// Bind the values to the query
	$bind = mysqli_stmt_bind_param($query, "i", $id);
	if ($bind === false) { trigger_error('Bind parameters failed ' . E_USER_ERROR); }

	// Execute the query. It if works, redirect the user back to the Customers page with a message
	$exec = mysqli_stmt_execute($query);
	if ($exec === false) {
		trigger_error('Statement execution failed ' . htmlspecialchars(mysqli_error($query)), E_USER_ERROR);
	} else {
		echo 'Customer Id ' . $id . ' has been removed from the database';
		$_SESSION['message'] = "deleted";
		$_SESSION['name'] = $cuFName . " " . $cuLName;
		redirect_to("customersToDelete.php");
	}

	mysqli_close($connection);

	include ("../includes/layouts/footer.php");
?>