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

	$query = 'DELETE FROM hyperav_products WHERE prModelNo = "' . $modelNo . '"';
	$results = @mysqli_query($connection, $query);
	$num_rows = mysqli_affected_rows($connection);

	/* If the product was successfully deleted, the user is redirected back to 
	 the products page and a flag is set so that that page can inform the user */
	if ($results) {
		if ($num_rows == 1) {
			echo 'Item ' . $modelNo . ' has been removed from the database';
			$_SESSION['message'] = "deleted";
			$_SESSION['modelNo'] = $modelNo;
			redirect_to("products.php");
		} else {
			echo '<p>The product was not deleted</p>';
			// echo '<p>' . mysqli_error($connection) . '</p>';
		}
	} else {
		echo 'There was a database error';
		// echo '<p>' . mysqli_error($connection) . '</p>';
	}
?>


<?php
	include ("../includes/layouts/footer.php");
?>