<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	if (!isset($_SESSION['staff'])) {
		redirect_to("index.php");
	}

	// Get the customer number from the POST request
	$id = $_POST['customerID'];

	$page_title = $id . ' | HyperAV';
	include ("../includes/layouts/header.php");

	$query = 'DELETE FROM hyperav_customer WHERE customerID = ' . $id;
	$results = @mysqli_query($connection, $query);
	$num_rows = mysqli_affected_rows($connection);

	if ($results) {
		if ($num_rows == 1) {
			echo 'Customer Id ' . $id . ' has been removed from the database';
			$_SESSION['message'] = "deleted";
			$_SESSION['id'] = $id;
			redirect_to("customersToDelete.php");
		} else {
			echo '<p>The customer was not deleted</p>';
			//echo '<p>' . mysqli_error($connection) . '</p>';
		}
	} else {
		echo 'There was a database error';
		//echo '<p>' . mysqli_error($connection) . '</p>';
	}
?>


<?php
	include ("../includes/layouts/footer.php");
?>