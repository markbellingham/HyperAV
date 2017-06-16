<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	$page_title = 'Edit Customer Profile';
	include ("../includes/layouts/header.php");
	//echo '<p>First Name: ' .$_SESSION['first_name']. '</p>';;
	//echo '<p>Email: ' .$_SESSION['email']. '</p>';

	if (!isset($_SESSION['staff'])) {
		redirect_to("index.php");
	}
?>


<h3>Customer Information </h3>


<?php
	$query = 'SELECT * FROM hyperAV_customer WHERE cuFName = "' . $_SESSION['first_name'] . '" AND cuEmail="' . $_SESSION['email'] . '";';
	$result=@mysqli_query($connection,$query);
	$num_rows = mysqli_num_rows($result);
	
	if ($result) {
		if ($num_rows > 0) {
			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
				echo'<form action="updateCustomer.php" method="post">	';
				echo '<tr><td><p><input type="hidden" name="id" value="' . $row['customerID'] . '"></p></td></tr>';
					echo '<tr><td><p>First Name : <input type="text" name="cuFName" value="' . $row['cuFName'] . '"></p></td></tr>';
				echo '<tr><td><p>Last Name : <input type="text" name="cuLName" value="' . $row['cuLName'] . '"></p></td></tr>';
				echo '<tr><td><p>Address Line 1 : <textarea name="address1" rows="5" cols="30">' . $row['cuAddress1'] . '</textarea></p></td></tr>';
				echo '<tr><td><p>Address Line 2 :<textarea name="address2" rows="5" cols="30">' . $row['cuAddress2'] . '</textarea></p></td></tr>';
				echo '<tr><td><p>Town : <input type="text" name="cuTown" value="' . $row['cuTown'] . '"></p></td></tr>';
				echo '<tr><td><p>Postcode : <input type="text" name="cuPostcode" value="' . $row['cuPostcode'] . '"></p></td></tr>';
				echo '<tr><td><p>Telephone Number : <input type="text" name="cuTelephone" value="' . $row['cuTelephone'] . '"></p></td></tr>';
				echo '<tr><td><p>Email Id: <input type="email" name="cuEmail" value="' . $row['cuEmail'] . '"></p></td></tr>';
				echo '<tr><td></td></tr>';
				echo '<input type="submit" value="Change Details"></form></td>';
				
				echo '</tr></table></div>';
				//
			}
		} else {
			echo '<p class="error">This customer does not exist.</p>';
			echo '<p>' . mysqli_error($connection) . '</p>';
		}
	} else {
		echo '<h3 class="error">System Error</h3>
		<p class="error">customer information could not be retrieved.</p>';
		echo '<p>' . mysqli_error($connection) . '</p>';
	}	

	mysqli_free_result($result);
	mysqli_close($connection);

	include ("../includes/layouts/footer.php");
?>