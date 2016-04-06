<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	$page_title = 'Profile Edit- HyperAV Customer';
	include ("../includes/layouts/header.php");

	if (!isset($_SESSION['staff'])) {
	redirect_to("index.php");
}

	$id				= $_POST['customerID'];
	$lname 			= $_POST['cuLName'];
	$address1 		= $_POST['address1'];
	$address2 		= $_POST['address2'];
	$town 			= $_POST['cuTown'];
	$postcode 		= $_POST['cuPostcode'];
	$telephone 		= $_POST['cuTelephone'];
	$cuEmail		= $_POST['cuEmail'];

	$page_title = 'Customer edited! | HyperAV';
	
	 echo '<p>' . $id . '</p>';
	 echo '<p>' . $lname . '</p>';
	 echo '<p>' . $address1 . '</p>';
	 echo '<p>' . $address2 . '</p>';
	 echo '<p>' . $town . '</p>';
	 echo '<p>' . $postcode . '</p>';
	 echo '<p>' . $telephone . '</p>';
	 echo '<p>' . $cuEmail . '</p>';
	
	$query = 'UPDATE hyperav_customer SET cuLName ="' . $lname . '", cuAddress1 = "' . $address1 . '", cuAddress2 = "' . $address2 . '", cuTown = "' . $town . '", cuPostcode = "' . $postcode . '", cuTelephone = "' . $telephone . '" WHERE customerID = ' . $id;//cuLName = "' . $lname . '" AND cuPostcode = "' . $postcode . '"';
	$results = @mysqli_query($connection, $query);
	$num_rows = mysqli_affected_rows($connection);

	//echo '<p>' . $query . '</p>';

	if ($results) {
		if($num_rows > 0) 
		{
			echo '<p>customer information for ' . $lname . ' has been updated</p>';
			$_SESSION['message'] = 'edited';
			//header ('Location: selected_product.php?prModelNo=' . $modelNo);
		} 
		else 
		{
			echo '<p>Customer information for ' . $lname . ' could not be updated</p>';
			echo '<p>' . mysqli_error($connection) . '</p>';
		}
	} 
	else 
	{
		echo '<p>There was an error with the database</p>';
		echo '<p>' . mysqli_error($connection) . '</p>';
	}

		/*	} else {
				echo '<p class="error">This customer does not exist.</p>';
			}
		} else {
			echo '<h3 class="error">System Error</h3>
			<p class="error">customer information could not be retrieved.</p>';
			//DEBUGGING echo '<p class="error">'.mysqli_error($connection).'</p>
			//DEBUGGING <p class="error">Query:'. $query . '</p>';
		}
*/
	mysqli_free_result($results);
	mysqli_close($connection);
?>


<?php
	include ("../includes/layouts/footer.php");
?>