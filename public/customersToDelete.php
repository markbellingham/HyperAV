<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	$page_title = 'Customers | HyperAV';
	include ("../includes/layouts/header.php");

	if (!isset($_SESSION['staff'])) {
		redirect_to("index.php");
	}

	// If the user came from the delete_item.php page, there will be a message in the SESSION and
	// the page will tell the user which customer was deleted.
	// Then it deletes the SESSION variable so that the message does not reappear.
	if (isset($_SESSION['message'])) {
		if ($_SESSION['message'] == "deleted") {
			echo '<h3>' . $_SESSION['name'] . ' was deleted from the database</h3>';
			unset($_SESSION['message']);
		}
	}
?>


<div id="main">
<h3>Customers</h3>


<?php 
	
	$query = "SELECT * FROM hyperAV_customer";
	
	$results = @mysqli_query($connection, $query);
	$num_rows = mysqli_num_rows($results);
	
	if ($results) {
		if ($num_rows > 0) {

			// If there are results, they are displayed in a table
			echo '<table class="customers">
			<tr><th>Customer Id</th><th>First Name</th><th>Last Name</th><th>Address1</th><th>Address2</th><th>Town</th><th>PostCode</th><th>Telephone</th><th>Email</th><th></th></tr>';

			while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
				echo '<tr><td>' . $row['customerID']. '</td>
						<td>' . $row['cuFName']. ' </td>
						<td>' . $row['cuLName'] . '</td>
						<td>' . $row['cuAddress1'] . '</td>
						<td>' . $row['cuAddress2'] . '</td>
						<td>' . $row['cuTown'] . '</td>
						<td>' . $row['cuPostcode'] . '</td>
						<td>' . $row['cuTelephone'] . '</td>
						<td>' . $row['cuEmail'] . '</td><td>
					<form action="deleteCustomer.php" method="POST">
					<input type="hidden" name="customerID" value=' . $row['customerID'] . '>
					<input type="hidden" name="cuFName" value=' . $row['cuFName'] . '>
					<input type="hidden" name="cuLName" value=' . $row['cuLName'] . '>
					<input type="submit" value="Remove Customer"></form></td></tr>';
			}
			echo '</table>';

		} else {
			// If the system was able to query the database but returned no results, we end up here
			echo '<p class="error">There are no customers.</p>';
		}
	} else {
		// If there was a a problem with the database query itself, we end up here.
		echo '<h3 class="error">System Error</h3>
		<p class="error">Customer data could not be retrieved.</p>';
		//DEBUGGING
		 echo '<p class="error">'.mysqli_error($connection).'</p>';
		//DEBUGGING
		 echo '<p class="error">Query:'. $query . '</p>';
	}

	// Clean up variables and close the connection
	mysqli_free_result($results);
	//mysqli_free_result($results1);
	mysqli_close($connection);
?>


</div> <!-- ends main -->


<?php
	include ("../includes/layouts/footer.php");
?>