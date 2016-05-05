<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	$page_title = 'Profile Edit- HyperAV Customer';
	include ("../includes/layouts/header.php");

	if (!isset($_SESSION['staff'])) {
	redirect_to("index.php");
}

	$id				= (int)$_POST['customerID'];
	$fname			= $_POST['cuFName'];
	$lname 			= $_POST['cuLName'];
	$address1 		= $_POST['address1'];
	$address2 		= $_POST['address2'];
	$town 			= $_POST['cuTown'];
	$postcode 		= $_POST['cuPostcode'];
	$telephone 		= $_POST['cuTelephone'];
	$cuEmail		= $_POST['cuEmail'];

	$page_title = 'Customer edited! | HyperAV';
	
	 // echo '<p>' . $id . ' ' . gettype($id) . '</p>';
	 // echo '<p>' . $lname . '</p>';
	 // echo '<p>' . $address1 . '</p>';
	 // echo '<p>' . $address2 . '</p>';
	 // echo '<p>' . $town . '</p>';
	 // echo '<p>' . $postcode . '</p>';
	 // echo '<p>' . $telephone . '</p>';
	 // echo '<p>' . $cuEmail . '</p>';
	
	// Create the query statement using prepared statement
	$query = mysqli_prepare($connection, 'UPDATE hyperav_customer SET cuFName = ?, cuLName = ?, cuAddress1 = ?, cuAddress2 = ?, cuTown = ?, cuPostcode = ?, cuTelephone = ?, cuEmail = ? WHERE customerID = ?');
	if ($query === false) { trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($connection)), E_USER_ERROR); }

	// Bind the variables to the query
	$bind = mysqli_stmt_bind_param($query, "ssssssssi", $fname, $lname, $address1, $address2, $town, $postcode, $telephone, $cuEmail, $id);
	if ($bind === false) { trigger_error('Binding parameters failed! ' . E_USER_ERROR); }

	// Execute the query. If it succeeds, display a message to the user
	$exec = mysqli_stmt_execute($query);
	if ($exec === false) {
		trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($query)), E_USER_ERROR);
	} else {
		echo '<p>customer information for ' . $fname . ' ' . $lname . ' has been updated</p>';
		$_SESSION['message'] = 'edited';
	}

	mysqli_close($connection);

	include ("../includes/layouts/footer.php");
?>